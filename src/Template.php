<?php

class Template
{
    public $htmlFunction;

    public function __construct(callable $htmlFunction)
    {
        $this->htmlFunction = $htmlFunction;
    }

    public function render(...$args): void
    {
        $html = ($this->htmlFunction)(...$args);
        echo $html;
    }
}