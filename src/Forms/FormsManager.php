<?php

declare(strict_types=1);

namespace Laniakea\Forms;

use Illuminate\Support\MessageBag;
use Laniakea\Forms\Interfaces\FormButtonInterface;
use Laniakea\Forms\Interfaces\FormFieldInterface;
use Laniakea\Forms\Interfaces\FormIdsGeneratorInterface;
use Laniakea\Forms\Interfaces\FormInterface;
use Laniakea\Forms\Interfaces\FormSectionInterface;
use Laniakea\Forms\Interfaces\FormsManagerInterface;

class FormsManager implements FormsManagerInterface
{
    /**
     * Required to force empty arrays to be objects in the JSON output.
     * We're not using json_encode flag JSON_FORCE_OBJECT because it
     * would convert valid empty arrays into JSON objects as well.
     */
    private bool $forceEmptyArraysToObjects = false;

    public function __construct(private readonly FormIdsGeneratorInterface $idsGenerator)
    {
        //
    }

    /**
     * Generates form data that will be used in frontend.
     *
     * @param FormInterface $form
     *
     * @return array
     */
    public function getFormData(FormInterface $form): array
    {
        $this->forceEmptyArraysToObjects = false;

        return [
            'form' => $this->getForm($form),
            'sections' => $this->getFormSections($form->getSections(), $form->getFields()),
        ];
    }

    /**
     * Generates JSON string for the form.
     *
     * @param FormInterface $form
     *
     * @return string
     */
    public function getFormJson(FormInterface $form): string
    {
        $this->forceEmptyArraysToObjects = true;

        return json_encode([
            'form' => $this->getForm($form),
            'sections' => $this->getFormSections($form->getSections(), $form->getFields()),
        ]);
    }

    /**
     * Convert empty arrays into instance of \stdClass if $forceEmptyArraysToObjects is true.
     *
     * @param array $possiblyEmptyArray
     *
     * @return array|\stdClass
     */
    protected function getValidJsonObject(array $possiblyEmptyArray): array|\stdClass
    {
        if (!$this->forceEmptyArraysToObjects) {
            return $possiblyEmptyArray;
        }

        return empty($possiblyEmptyArray) ? new \stdClass() : $possiblyEmptyArray;
    }

    /**
     * Get form data for JSON encoding.
     *
     * @param FormInterface $form
     *
     * @return array
     */
    protected function getForm(FormInterface $form): array
    {
        return [
            'id' => $form->getId() ?? $this->idsGenerator->getFormId(),
            'layout' => $form->getLayout(),
            'method' => $form->getMethod(),
            'url' => $form->getUrl(),
            'redirect_url' => $form->getRedirectUrl(),
            'headers' => $this->getValidJsonObject($form->getHttpHeaders()),
            'buttons' => $this->getFormButtons($form->getButtons()),
            'settings' => $this->getValidJsonObject($form->getSettings()),
            'values' => $this->getValidJsonObject($form->getValues()),
            'errors' => $this->getFormErrors($form->getErrors()),
        ];
    }

    /**
     * Get form buttons list for JSON encoding.
     *
     * @param array|FormButtonInterface[] $buttons
     *
     * @return array
     */
    protected function getFormButtons(array $buttons): array
    {
        return array_map(fn (FormButtonInterface $button) => [
            'type' => $button->getType()->value,
            'label' => $button->getLabel(),
            'url' => $button->getUrl(),
            'settings' => $this->getValidJsonObject($button->getSettings()),
        ], $buttons);
    }

    /**
     * Get morm sections list for JSON encoding.
     *
     * @param array|FormSectionInterface[] $sections
     * @param array|FormFieldInterface[]   $fields
     *
     * @return array
     */
    protected function getFormSections(array $sections, array $fields): array
    {
        if (!count($fields)) {
            return [];
        } elseif (!count($sections)) {
            // For has no sections, wrap all fields into a single unlabeled section.

            return [[
                'id' => $this->idsGenerator->getSectionId(null),
                'label' => null,
                'description' => null,
                'fields' => $this->getFormFields($fields),
            ]];
        }

        return collect($sections)->map(function (FormSectionInterface $section) use ($fields) {
            $sectionFields = $this->getSectionFields($fields, $section->getFieldNames());

            if (!count($sectionFields)) {
                return null;
            }

            return [
                'id' => $section->getId() ?? $this->idsGenerator->getSectionId($section->getLabel()),
                'label' => $section->getLabel(),
                'description' => $section->getDescription(),
                'fields' => $this->getFormFields($sectionFields),
            ];
        })->reject(null)->values()->toArray();
    }

    /**
     * Get list of fields for the section.
     *
     * @param array $fields
     * @param array $names
     *
     * @return array
     */
    protected function getSectionFields(array $fields, array $names): array
    {
        // Not using Arr::only() to keep the section's fields order.

        $sectionFields = [];

        foreach ($names as $name) {
            if (!isset($fields[$name])) {
                continue;
            }

            $sectionFields[$name] = $fields[$name];
        }

        return $sectionFields;
    }

    /**
     * Get form fields list for JSON encoding.
     *
     * @param array|FormFieldInterface[] $fields
     *
     * @return array
     */
    protected function getFormFields(array $fields): array
    {
        return collect($fields)
            ->map(fn (FormFieldInterface $field, string $name) => [
                'id' => $field->getId() ?? $this->idsGenerator->getFieldId($name, $field->getLabel()),
                'type' => $field->getType(),
                'name' => $name,
                'label' => $field->getLabel(),
                'hint' => $field->getHint(),
                'settings' => $this->getValidJsonObject($field->getSettings()),
            ])
            ->values()
            ->toArray();
    }

    /**
     * Get form errors list for JSON encoding.
     *
     * @param MessageBag|null $errors
     *
     * @return array|null
     */
    protected function getFormErrors(?MessageBag $errors): ?array
    {
        if (is_null($errors)) {
            return null;
        }

        return $this->getValidJsonObject($errors->toArray());
    }
}
