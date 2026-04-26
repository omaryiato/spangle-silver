<?php

namespace App\Repositories\Dashboard;

use App\Models\LookupValue;

class LookupValueRepository
{
    public function getLookupValueList()
    {
        return LookupValue::all();
    }

    public function getLookupValueDetails($id)
    {
        return LookupValue::findOrFail($id);
    }

    public function addNewLookupValue($lookup_value_request)
    {
        return LookupValue::create($lookup_value_request);
    }

    public function updateLookupValue(LookupValue $lookupValue, $lookup_value_request)
    {
        $lookupValue->update($lookup_value_request);
        return $lookupValue;
    }

    public function deleteLookupValue(LookupValue $lookupValue)
    {
        return $lookupValue->delete();
    }
}
