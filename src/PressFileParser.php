<?php


namespace alex552\Press;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;

class PressFileParser
{
    protected $filename;

    protected $rawData;

    protected $data;

    public function __construct($filename)
    {
        $this->filename = $filename;

        $this->splitFile();
        $this->explodeData();
        $this->processFields();
    }
    /**
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     *
     * @return mixed
     */
    public function getRawData()
    {
        return $this->rawData;
    }
    /**
     *
     * @return void
     */
    protected function splitFile()
    {
        preg_match('/^\-{3}(.*?)\-{3}(.*)/s',
            File::exists($this->filename) ? File::get($this->filename) : $this->filename,
            $this->rawData
        );
    }
    /**
     *
     * @return void
     */
    protected function explodeData()
    {
        foreach (explode("\n", trim($this->rawData[1])) as $fieldString) {
            preg_match('/(.*):\s?(.*)/', $fieldString, $fieldArray);
            $this->data[$fieldArray[1]] = $fieldArray[2];
        }
        $this->data['body'] = trim($this->rawData[2]);
    }
    /**
     *
     * @return void
     * @throws \ReflectionException
     */
    protected function processFields()
    {

        foreach ($this->data as $field => $value) {
            $class = 'alex552\\Press\\Fields\\'.Str::title($field);

            if ( ! class_exists($class) && ! method_exists($class, 'process')) {
                $class = 'alex552\\Press\\Fields\\Extra';
            }
            $this->data = array_merge(
                $this->data,
                $class::process($field, $value, $this->data)
            );
        }
    }
    /**
     *
     * @param $field
     *
     * @return string
     * @throws \ReflectionException
     */
    private function getField($field)
    {
        foreach (Press::availableFields() as $availableField) {
            $class = new ReflectionClass($availableField);
            if ($class->getShortName() == $field) {
                return $class->getName();
            }
        }
    }
}