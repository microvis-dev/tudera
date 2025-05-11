<?php

namespace App\Enums;

enum WorkspaceColumnTypeEnum: string {
    case STRING = 'string';
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case DATETIME = 'datetime';
    case STATUS = 'status';
    case REF = 'ref';
    /*
    case DATE = 'date'; 
    case URL = 'url';
    case BOOLEAN = 'boolean';
    case EMAIL = 'email';
    */
    
    /**
     * Get validation rule for this column type
     * @return string
     */
    public function getValidationRule(): string
    {
        return match($this) {
            self::STRING => 'required|string',
            self::INTEGER => 'required|integer',
            self::FLOAT => 'required|numeric',
            self::DATETIME => 'required|date',
            self::STATUS => 'required|string',
            self::REF => 'required|string',
            /*
            self::DATE => 'required|date',
            self::URL => 'required|url',
            self::BOOLEAN => 'required|boolean',
            self::EMAIL => 'required|email',
            */
        };
    }
}