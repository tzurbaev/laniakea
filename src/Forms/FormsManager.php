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

readonly class FormsManager implements FormsManagerInterface
{
    public function __construct(private FormIdsGeneratorInterface $idsGenerator)
    {
        //
    }

    public function getFormData(FormInterface $form): array
    {
        return [
            'form' => $this->getForm($form),
            'sections' => $this->getFormSections($form->getSections(), $form->getFields()),
        ];
    }

    protected function getForm(FormInterface $form): array
    {
        return [
            'id' => $form->getId() ?? $this->idsGenerator->getFormId(),
            'layout' => $form->getLayout(),
            'method' => $form->getMethod(),
            'url' => $form->getUrl(),
            'redirect_url' => $form->getRedirectUrl(),
            'headers' => $form->getHttpHeaders(),
            'buttons' => $this->getFormButtons($form->getButtons()),
            'settings' => $form->getSettings(),
            'values' => $form->getValues(),
            'errors' => $this->getFormErrors($form->getErrors()),
        ];
    }

    /** @param array|FormButtonInterface[] $buttons */
    protected function getFormButtons(array $buttons): array
    {
        return array_map(fn (FormButtonInterface $button) => [
            'type' => $button->getType()->value,
            'label' => $button->getLabel(),
            'url' => $button->getUrl(),
            'settings' => $button->getSettings(),
        ], $buttons);
    }

    /**
     * @param array|FormSectionInterface[] $sections
     * @param array|FormFieldInterface[]   $fields
     */
    protected function getFormSections(array $sections, array $fields): array
    {
        if (!count($fields)) {
            return [];
        } elseif (!count($sections)) {
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

    protected function getSectionFields(array $fields, array $names): array
    {
        // Not using Arr::only() to keep the section's order of the fields.

        $sectionFields = [];

        foreach ($names as $name) {
            if (!isset($fields[$name])) {
                continue;
            }

            $sectionFields[$name] = $fields[$name];
        }

        return $sectionFields;
    }

    /** @param array|FormFieldInterface[] $fields */
    protected function getFormFields(array $fields): array
    {
        return collect($fields)
            ->map(fn (FormFieldInterface $field, string $name) => [
                'id' => $field->getId() ?? $this->idsGenerator->getFieldId($name, $field->getLabel()),
                'type' => $field->getType(),
                'name' => $name,
                'label' => $field->getLabel(),
                'hint' => $field->getHint(),
                'settings' => $field->getSettings(),
            ])
            ->values()
            ->toArray();
    }

    protected function getFormErrors(?MessageBag $errors): ?array
    {
        if (is_null($errors)) {
            return null;
        }

        return $errors->toArray();
    }
}
