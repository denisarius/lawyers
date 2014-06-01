<?php

class View
{
	final protected function _renderTemplate($params, $template)
	{
		$paramNames = array();
		$paramValues = array();
		foreach ($params as $name => $value)
		{
			$paramNames[] = "@$name@";
			$paramValues[] = $value;
		}

		return str_replace($paramNames, $paramValues, $template);
	}
}