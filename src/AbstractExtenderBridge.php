<?php

namespace CEKW\WpPluginFramework\Core;

abstract class AbstractExtenderBridge
{
    private array $extends = [];

    protected function addExtend($instance): AbstractExtenderBridge
    {
        $this->extends[] = $instance;
        return $this;
    }

    public function __call($method, $args)
    {
        $result = array_map(function ($instance) use ($method, $args) {
            if (method_exists($instance, $method)) {
                return call_user_func_array([$instance,$method], $args);
            }
        }, $this->extends);

        return count($result) === 1 ? $result[0] : $result;
    }
}