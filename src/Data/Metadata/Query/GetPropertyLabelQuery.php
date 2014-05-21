<?php

namespace Layer\Data\Metadata\Query;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Mapping\ClassMetadata;
use Layer\Utility\InflectorInterface;

class GetPropertyLabelQuery extends PropertyAnnotationQuery {

	protected $inflector;

	protected $annotationClass = 'Layer\\Data\\Metadata\\Annotation\\PropertyLabel';

	public function __construct(Reader $reader, InflectorInterface $inflector) {
		parent::__construct($reader);
		$this->inflector = $inflector;
	}

	public function getName() {
		return 'getPropertyLabel';
	}

	protected function getResultFromAnnotation(ClassMetadata $classMetadata, $annotation, array $options) {
		if(empty($annotation->value)) {
			return $this->getFallbackResult($classMetadata, $options);
		}
		return $annotation->value;
	}

	protected function getFallbackResult(ClassMetadata $classMetadata, array $options) {
		$reflProperty = $classMetadata->getReflectionProperty($options['property']);
		return ucfirst($this->inflector->humanize($reflProperty->getName()));
	}

}