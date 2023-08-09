<?php

namespace SchedulingTerms\App\Dto;

use SchedulingTerms\App\Dto\Jobs\JobDto;

class BaseDto
{
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    public function setProperties(array $data): void
    {
        foreach ($data as $property => $value) {
            if (property_exists($this, $property)) {
                if (is_subclass_of($this->$property, BaseDto::class)) {
                    if (is_array($value)) {
                        if ($this->$property === null) {
                            $nestedDto = new $this->$property;
                            $nestedDto->setProperties($value);
                            $this->$property = $nestedDto;
                        } else {
                            $this->$property->setProperties([$value]);
                        }
                    }
                } else {
                    $this->$property = $value;
                }
            }
        }
    }
    public static function from(array $arr): static|array
    {
        if(count($arr) > 1) {
            $data = [];
            foreach ($arr as $a) {
                $job = new static();
                $job->setProperties($a);
                $data[] = $job;
            }

            return $data;
        }

        $data = new static();
        $data->setProperties($arr);

        return $data;
    }

}