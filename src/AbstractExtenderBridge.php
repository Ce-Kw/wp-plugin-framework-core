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
        $funcResults = array_map(function ($instance) use ($method, $args) {
            if (method_exists($instance, $method)) {
                return call_user_func_array([$instance,$method], $args);
            }
            return null;
        }, $this->extends);

        if (substr($method, 0, 3) === 'set') {
            return $this;
        }

        $result = [];
        foreach ($funcResults as $key => $value) {
            if ($value === null) {
                continue;
            }
            $result[] = $value;
        }

        return count($result) === 1 ? $result[array_key_first($result)] : $result;
    }
}