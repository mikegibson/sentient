<?php

namespace Layer\Node;

use Symfony\Component\HttpFoundation\Request;

interface ControllerNodeInterface extends NodeInterface {

	public function getRouteName();

	public function getActionName();

	public function getActionLabel();

	public function invoke(Request $request);

	public function getTemplate();

	public function isAccessible();

	public function isVisible();

	public function getVisibleChildNodes();

}