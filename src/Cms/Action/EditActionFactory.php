<?php

namespace Sentient\Cms\Action;

use Sentient\Data\ManagedRepositoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EditActionFactory implements RepositoryCmsActionFactoryInterface {

	private $formFactory;

	private $urlGenerator;

	public function __construct(FormFactoryInterface $formFactory, UrlGeneratorInterface $urlGenerator) {
		$this->formFactory = $formFactory;
		$this->urlGenerator = $urlGenerator;
	}

	public function isRepositoryEligible(ManagedRepositoryInterface $repository) {
		$crud = $repository->queryMetadata('getEntityCrud');
		return !empty($crud->update);
	}

	public function createAction(ManagedRepositoryInterface $repository) {
		return new EditAction($repository, $this->formFactory, $this->urlGenerator);
	}

}