<?php

namespace CmsIr\Slider\View;

use Zend\Form\View\Helper\FormInput as ZendFormInput;

use Zend\Form\ElementInterface;
use Zend\Form\Exception;
use Zend\Form\View\Helper\FormElementErrors;


class FormInput extends ZendFormInput
{
    public function render(ElementInterface $element)
    {
        $name = $element->getName();
        if ($name === null || $name === '') {
            throw new Exception\DomainException(sprintf(
                '%s requires that the element has an assigned name; none discovered',
                __METHOD__
            ));
        }

        $attributes          = $element->getAttributes();
        $attributes['name']  = $name;
        $attributes['type']  = $this->getType($element);
        $attributes['value'] = $element->getValue();

        switch ($this->getType($element)) {
            case 'hidden' :
                return sprintf(
                    '<input %s class="form-control" %s  ',
                    $this->createAttributesString($attributes),
                    $this->getInlineClosingBracket()

                );
            break;
            case 'file' :
                return sprintf(
                    '<input %s class="form-control" %s  ',
                    $this->createAttributesString($attributes),
                    $this->getInlineClosingBracket()

                );
            break;
            case 'submit' :
                return sprintf(
                    '<input %s class="form-control" %s  ',
                    $this->createAttributesString($attributes),
                    $this->getInlineClosingBracket()

                );
            break;
            default:
                $error = new FormElementErrors();

                $errorMessage =  $error->setMessageOpenFormat('<small data-bv-validator="notEmpty" class="help-block" style="color: #E9573F">')
                    ->setMessageCloseString('</small>')
                    ->render($element);

                $options = $element->getOptions();
                $options = reset($options);

                return sprintf(
                    '<div class="form group ' . (count($element->getMessages()) > 0 ? 'has-error' : '') . ' has-feedback">
                    <label> %s </label><input %s class="form-control" %s
                    ' . (count($element->getMessages()) > 0 ? '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' : '') . '</div> %s',
                    $options,
                    $this->createAttributesString($attributes),
                    $this->getInlineClosingBracket(),
                    $errorMessage
                );
            break;
        }

    }
}