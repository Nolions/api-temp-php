<?php

namespace App\Logger;

use Monolog\Formatter\JsonFormatter as BaseJsonFormatter;

class JsonFormatter extends BaseJsonFormatter
{
    public function format(array $record): string
    {
        $record = $this->normalize($record);

        $log = [
            'level' => $record['level_name'],
            'time' => $record['datetime'],
            'message' => $record['message'],
        ];
        if (!empty($record['context'])) {
            $log['context'] = $record['context'];
        }
        if (!empty($record['extra'])) {
            $log['extra'] = $record['extra'];
        }

        return $this->toJson($log, true).($this->appendNewline ? PHP_EOL : '');
    }
}
