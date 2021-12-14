<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class ErrorDataProvider
{
    /** @var array */
    protected $errors = [];


    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }


    /**
     * @param array $errors
     * @return ErrorDataProvider
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }


    /**
     * @return ErrorDataProvider
     */
    public function unsetErrors()
    {
        $this->errors = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasErrors()
    {
        return ($this->errors !== null && $this->errors !== []);
    }


    /**
     * @param string $Error
     * @return ErrorDataProvider
     */
    public function addError(string $Error)
    {
        $this->errors[] = $Error; return $this;
    }


    /**
     * @return array
     */
    protected function getElements(): array
    {
        return array (
          'errors' =>
          array (
            'name' => 'errors',
            'allownull' => false,
            'default' => '',
            'type' => 'array',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
            'singleton' => 'Error',
            'singleton_type' => 'array',
          ),
        );
    }
}
