<?php

namespace Sentient\Cms;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Sentient\Action\ReskinnedAction;
use Sentient\Cms\Action\AddActionFactory;
use Sentient\Cms\Action\EditActionFactory;
use Sentient\Cms\Action\IndexActionFactory;
use Sentient\Cms\Data\CmsRepository;
use Sentient\Cms\Data\HtmlStripperDecorator;
use Sentient\Cms\Data\LinkerDecorator;
use Sentient\Cms\Data\Metadata\Query\GetCmsNodePathQuery;
use Sentient\Cms\Node\CmsNavigationNode;
use Sentient\Cms\Node\RepositoryCmsNodeFactory;
use Sentient\Cms\Data\Metadata\Query\GetCmsEntityQuery;
use Sentient\Cms\Data\Metadata\Query\GetCmsEntitySlugQuery;
use Sentient\Cms\View\CmsHelper;
use Sentient\Cms\View\TwigCmsExtension;
use Sentient\Data\ManagedRepositoryEvent;
use Sentient\Data\Metadata\QueryCollection;
use Sentient\Data\TableData\ChainedTableDataDecorator;
use Sentient\Data\TableData\EscaperDecorator;
use Sentient\Data\TableData\StringifierDecorator;
use Sentient\Data\TableData\TruncatorDecorator;
use Sentient\Media\Image\FilterRegistry;
use Sentient\Media\Image\ImageTransformer;
use Sentient\Node\ControllerNode;
use Sentient\Plugin\Plugin;
use Silex\Application;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class CmsProvider
 *
 * @package Sentient\Cms\Provider
 */
class CmsPlugin extends Plugin {

	public function getName() {
		return 'cms';
	}

	public function register(Application $app) {

		if(!isset($app['cms.url_fragment'])) {
			$app['cms.url_fragment'] = 'cms';
		}

		$app['cms.controllers'] = $app->share(function() use($app) {
			return $app['nodes.controllers_factory']($app['cms.root_node'], 'node', false);
		});

		$app['cms.helper'] = $app->share(function() use($app) {
			return new CmsHelper($app['url_generator']);
		});

		$app['metadata.queries.getCmsEntity'] = $app->share(function() use($app) {
			return new GetCmsEntityQuery($app['annotations.reader']);
		});

		$app['metadata.queries.getCmsEntitySlug'] = $app->share(function() use($app) {
			return new GetCmsEntitySlugQuery($app['metadata.queries.getCmsEntity'], $app['inflector']);
		});

		$app['metadata.queries.getCmsNodePath'] = $app->share(function() use($app) {
			return new GetCmsNodePathQuery($app['annotations.reader']);
		});

		$app['metadata.queries'] = $app->share($app->extend('metadata.queries',
			function(QueryCollection $collection) use($app) {
				$collection
					->registerQuery($app['metadata.queries.getCmsEntity'])
					->registerQuery($app['metadata.queries.getCmsEntitySlug'])
					->registerQuery($app['metadata.queries.getCmsNodePath'])
				;
				return $collection;
			}
		));

		$app['paginator.decorators.cmsTable'] = $app->share(function() use($app) {
			return new ChainedTableDataDecorator([
				new StringifierDecorator(),
				new HtmlStripperDecorator($app['orm.rm']),
				new TruncatorDecorator($app['string_helper']),
				new EscaperDecorator()
			]);
		});

		$app['paginator.decorators.cmsIndex'] = $app->share(function() use($app) {
			return new ChainedTableDataDecorator([
				$app['paginator.decorators.cmsTable'],
				new LinkerDecorator($app['orm.rm'], $app['url_generator'])
			]);
		});

		$app['cms.action_factories'] = $app->share(function() use($app) {
			return [
				new IndexActionFactory($app['paginator.decorators.cmsIndex'], $app['property_accessor']),
				new AddActionFactory($app['form.factory'], $app['url_generator']),
				new EditActionFactory($app['form.factory'], $app['url_generator'])
			];
		});

		$app['cms.repository_node_factory'] = $app->share(function() use($app) {
			return new RepositoryCmsNodeFactory($app['cms.root_node'], $app['cms.action_factories']);
		});

		$app['cms.login_action'] = $app->share(function() use($app) {
			return new ReskinnedAction($app['users.login_action'], '@cms/view/login');
		});

		$app['cms.login_node'] = $app->share(function() use($app) {
			return new ControllerNode('cms_login', $app['cms.login_action']);
		});

		$app['cms.content_node'] = $app->share(function() use($app) {
			return new ControllerNode('cms', null, null, 'content', 'Content', null, true, false);
		});

		$app['cms.root_node'] = $app->share(function() use($app) {
			$node = new ControllerNode('cms');
			$node->wrapChildNode($app['cms.login_node']);
			$node->wrapChildNode($app['cms.content_node']);
			return $node;
		});

		$app['cms.navigation_list'] = $app->share(function() use($app) {
			$node = new CmsNavigationNode($app['cms.root_node'], $app['url_generator']);
			return $node;
		});

		$app['dispatcher'] = $app->share($app->extend('dispatcher', function(EventDispatcherInterface $dispatcher) use($app) {
			$dispatcher->addListener(ManagedRepositoryEvent::REGISTER, function(ManagedRepositoryEvent $event) use($app) {
				$baseRepository = $event->getRepository();
				$repository = new CmsRepository($baseRepository, $app['cms.repository_node_factory']);
				$event->setRepository($repository);
			});
			return $dispatcher;
		}));

		if(!isset($app['cms.access_rule'])) {
			$app['cms.access_rule'] = ['^/' . $app['cms.url_fragment'] . '/.*$', 'ROLE_ADMIN'];
		}

		$app['images.filters.cms_thumbnail'] = $app->share(function() use($app) {
			$filter = new ImageTransformer('cms-thumbnail');
			$filter->getTransformation()->thumbnail(new Box(60, 60), ImageInterface::THUMBNAIL_OUTBOUND);
			return $filter;
		});

	}

	public function boot(Application $app) {
		$fragment = $app['cms.url_fragment'];
		$app->mount("/{$fragment}", $app['cms.controllers']);
		$rules = $app['security.access_rules'];
		$rules[] = $app['cms.access_rule'];
		$app['security.access_rules'] = $rules;

		$app['twig'] = $app->share($app->extend('twig', function(\Twig_Environment $twig) use($app) {
			$twig->addExtension(new TwigCmsExtension($app['cms.helper']));
			return $twig;
		}));

		$app['images.filters'] = $app->share($app->extend('images.filters', function(FilterRegistry $filters) use($app) {
			$filters->addFilter($app['images.filters.cms_thumbnail']);
			return $filters;
		}));

		$app['assets.register_scss']('cms/main', '@cms/scss/main.scss');
		$app['assets.register_scss']('cms/header', '@cms/scss/header.scss');
		$app['assets.register_js']('cms/main', '@cms/js/cms.js');
		$app['assets.register_js']('cms/header', '@cms/js/header.js');
		$app['assets.register_js']('cms/panel', '@cms/js/panel.js');
		$app['assets.register_js']('cms/form', '@cms/js/form.js');

	}

}