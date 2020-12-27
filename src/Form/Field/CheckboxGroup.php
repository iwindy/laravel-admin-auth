<?php

namespace Iwindy\Auth\Form\Field;



class CheckboxGroup extends \Encore\Admin\Form\Field\Checkbox
{
    protected $view = 'admin-auth::checkboxGroup';

    /**
     * @var array
     */
    protected $relatedField = [];

    /**
     * @param string $related
     * @param string $field
     * @return $this
     */
    public function related($related, $field)
    {
        $this->relatedField = [$related, $field];

        return $this;
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        if ($this->options instanceof \Closure) {
            if ($this->form) {
                $this->options = $this->options->bindTo($this->form->model());
            }

            $this->options(call_user_func($this->options, $this->value, $this));
        }

        return $this->options;
    }


    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->addVariables([
            'column'       => $this->column,
            'checked'       => $this->checked,
            'inline'        => $this->inline,
            'checkAllClass' => uniqid('check-all-'),
            'options'       => $this->getOptions(),
            'relatedField'  => json_encode($this->relatedField),
        ]);

        $this->addCascadeScript();

        return parent::fieldRender();
    }
}
