<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private array $viewVariables = [];

    protected function addTitle(string $text): void
    {
        $this->addViewVariable('title', $text);
    }

    protected function addDescription(string $text): void
    {
        $this->addViewVariable('description', $text);
    }

    protected function addSuccessMessage(string $message): void
    {
        $this->addViewVariable('alertMessage', $message);
        $this->addViewVariable('alertType', 'success');
    }

    protected function addDangerMessage(string $message): void
    {
        $this->addViewVariable('alertMessage', $message);
        $this->addViewVariable('alertType', 'danger');
    }

    protected function addViewVariable(string $name, $variable): void
    {
        $this->viewVariables[$name] = $variable;
    }

    protected function addJsonViewVariable(string $name, $variable): void
    {
        $this->addViewVariable($name, json_encode($variable));
    }

    protected function view(string $name): View
    {
        return view($name, $this->viewVariables);
    }
}
