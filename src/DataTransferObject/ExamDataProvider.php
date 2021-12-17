<?php
declare(strict_types=1);

namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class ExamDataProvider
{
    /** @var string */
    protected $name;

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * @param string $name
     * @return ExamDataProvider
     */
    public function setName(?string $name = null)
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return ExamDataProvider
     */
    public function unsetName()
    {
        $this->name = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasName()
    {
        return ($this->name !== null && $this->name !== []);
    }

    /**
     * @return array
     */
    protected function getElements(): array
    {
        return [
            'name' =>
                [
                    'name' => 'name',
                    'allownull' => false,
                    'default' => '',
                    'type' => 'string',
                    'is_collection' => false,
                    'is_dataprovider' => false,
                    'isCamelCase' => false,
                ]
        ];
    }
}
