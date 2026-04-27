<?php

namespace App\View\admin\Components;

use Illuminate\View\Component;

class FormActions extends Component
{
    public $cancelRoute;
    public $submitText;
    public $confirmationTitle;
    public $confirmationMessage;

    /**
     * Create a new component instance.
     *
     * @param string $cancelRoute
     * @param string $submitText
     * @param string $confirmationTitle
     * @param string $confirmationMessage
     */
    public function __construct(
        $cancelRoute = '#',
        $submitText = 'Save',
        $confirmationTitle = 'Confirm Submission',
        $confirmationMessage = 'Are you sure you want to submit this form? These changes will be saved immediately.'
    ) {
        $this->cancelRoute = $cancelRoute;
        $this->submitText = $submitText;
        $this->confirmationTitle = $confirmationTitle;
        $this->confirmationMessage = $confirmationMessage;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.admin.form-actions');
    }
}