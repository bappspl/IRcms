<?php

namespace CmsIr\System\View\Helper;

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
//        $attributes['type']  = $this->getType($element);
        $attributes['value'] = $element->getValue();


        switch ($attributes['type']) {
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
            case 'checkbox' :
                return sprintf(
                    '<input %s class="form-control" %s  ',
                    $this->createAttributesString($attributes),
                    $this->getInlineClosingBracket()

                );
            break;
            case 'textarea' :
                $options = $element->getOptions();
                $options = reset($options);

                $value = $attributes['value'];

                $error = new FormElementErrors();

                $errorMessage =  $error->setMessageOpenFormat('<small data-bv-validator="notEmpty" class="help-block" style="color: #E9573F">')
                    ->setMessageCloseString('</small>')
                    ->render($element);

                return sprintf(
                    '<div class="form group"><label> %s </label><textarea %s class="form-control">%s</textarea></div> %s',
                    $options,
                    $this->createAttributesString($attributes),
                    $value,
                    $errorMessage
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
                    '<div class="form-group ' . (count($element->getMessages()) > 0 ? 'has-error' : '') . ' has-feedback">
                    <label> %s </label><input %s class="form-control" %s
                    ' . (count($element->getMessages()) > 0 ? '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' : '') . '%s</div>',
                    $options,
                    $this->createAttributesString($attributes),
                    $this->getInlineClosingBracket(),
                    $errorMessage
                );
            break;
        }

    }
}