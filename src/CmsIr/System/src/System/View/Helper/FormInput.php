<?php

namespace CmsIr\System\View\Helper;

use Zend\Form\View\Helper\FormInput as ZendFormInput;

use Zend\Form\ElementInterface;
use Zend\Form\Exception;
use Zend\Form\View\Helper\FormElementErrors;
use Zend\Stdlib\ArrayUtils;

class FormInput extends ZendFormInput
{
    protected $validOptionAttributes = array(
        'disabled' => true,
        'selected' => true,
        'label'    => true,
        'value'    => true,
    );
    protected $validSelectAttributes = array(
        'name'      => true,
        'autofocus' => true,
        'disabled'  => true,
        'form'      => true,
        'multiple'  => true,
        'required'  => true,
        'size'      => true
    );

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
        $attributes['value'] = $element->getValue();


        switch ($attributes['type']) {
            case 'hidden' :
                return sprintf(
                    '<input %s class="form-control" %s  ',
                    $this->createAttributesString($attributes),
                    $this->getInlineClosingBracket()

                );
            break;
            case 'select' :
                $error = new FormElementErrors();

                $options = $element->getOptions();
                $label = $options['label'];

                $options = $element->getValueOptions();

                if (($emptyOption = $element->getEmptyOption()) !== null) {
                    $options = array('' => $emptyOption) + $options;
                }

                $attributes = $element->getAttributes();
                $value      = $this->validateMultiValue($element->getValue(), $attributes);
                $this->validTagAttributes = $this->validSelectAttributes;

                $errorMessage =  $error->setMessageOpenFormat('<small data-bv-validator="notEmpty" class="help-block" style="color: #E9573F">')
                    ->setMessageCloseString('</small>')
                    ->render($element);

                return sprintf(
                    '<div class="form-group ' . (count($element->getMessages()) > 0 ? 'has-error' : '') . ' has-feedback">
                    <label> %s </label><select %s class="form-control">%s</select>' . (count($element->getMessages()) > 0 ? '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' : '') . '%s</div>',
                    $label,
                    $this->createAttributesString($attributes),
                    $this->renderOptions($options, $value),
                    $errorMessage
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
                    '<div class="form-group ' . (count($element->getMessages()) > 0 ? 'has-error' : '') . ' has-feedback"><label> %s </label><textarea %s class="form-control">%s</textarea> %s</div>',
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

    protected function validateMultiValue($value, array $attributes)
    {
        if (null === $value) {
            return array();
        }

        if (!is_array($value)) {
            return (array) $value;
        }

        if (!isset($attributes['multiple']) || !$attributes['multiple']) {
            throw new Exception\DomainException(sprintf(
                '%s does not allow specifying multiple selected values when the element does not have a multiple attribute set to a boolean true',
                __CLASS__
            ));
        }

        return $value;
    }

    public function renderOptions(array $options, array $selectedOptions = array())
    {
        $template      = '<option %s>%s</option>';
        $optionStrings = array();
        $escapeHtml    = $this->getEscapeHtmlHelper();

        foreach ($options as $key => $optionSpec) {
            $value    = '';
            $label    = '';
            $selected = false;
            $disabled = false;

            if (is_scalar($optionSpec)) {
                $optionSpec = array(
                    'label' => $optionSpec,
                    'value' => $key
                );
            }

            if (isset($optionSpec['options']) && is_array($optionSpec['options'])) {
                $optionStrings[] = $this->renderOptgroup($optionSpec, $selectedOptions);
                continue;
            }

            if (isset($optionSpec['value'])) {
                $value = $optionSpec['value'];
            }
            if (isset($optionSpec['label'])) {
                $label = $optionSpec['label'];
            }
            if (isset($optionSpec['selected'])) {
                $selected = $optionSpec['selected'];
            }
            if (isset($optionSpec['disabled'])) {
                $disabled = $optionSpec['disabled'];
            }

            if (ArrayUtils::inArray($value, $selectedOptions)) {
                $selected = true;
            }

            if (null !== ($translator = $this->getTranslator())) {
                $label = $translator->translate(
                    $label, $this->getTranslatorTextDomain()
                );
            }

            $attributes = compact('value', 'selected', 'disabled');

            if (isset($optionSpec['attributes']) && is_array($optionSpec['attributes'])) {
                $attributes = array_merge($attributes, $optionSpec['attributes']);
            }

            $this->validTagAttributes = $this->validOptionAttributes;
            $optionStrings[] = sprintf(
                $template,
                $this->createAttributesString($attributes),
                $escapeHtml($label)
            );
        }

        return implode("\n", $optionStrings);
    }
}