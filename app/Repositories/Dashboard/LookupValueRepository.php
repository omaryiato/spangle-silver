<?php

namespace App\Repositories\Dashboard;

use App\Models\LookupValue;

class LookupValueRepository
{
    public function getLookupValueList()
    {
        return LookupValue::with('type')->get();
    }

    public function getLookupValueDetails(LookupValue $lookupValue)
    {
        return $lookupValue->load('type');
    }

    public function addNewLookupValue(array $lookup_value_request)
    {
        return LookupValue::create($lookup_value_request);
    }

    public function updateLookupValue(LookupValue $lookupValue, array $lookup_value_request)
    {
        $lookupValue->update($lookup_value_request);
        return $lookupValue;
    }

    public function deleteLookupValue(LookupValue $lookupValue)
    {
        return $lookupValue->delete();
    }
}
