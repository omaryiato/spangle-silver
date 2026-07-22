<?php

namespace App\Repositories\Dashboard;

use App\Models\LookupType;

class LookupTypeRepository
{
    public function getLookupTypeList()
    {
        return LookupType::with('values')->get();
    }

    public function getLookupTypeDetails(LookupType $lookupType)
    {
        return $lookupType->load('values');
    }

    public function addNewLookupType(array $lookup_type_request)
    {
        return LookupType::create($lookup_type_request);
    }

    public function updateLookupType(LookupType $lookupType, array $lookup_type_request)
    {
        $lookupType->update($lookup_type_request);
        return $lookupType;
    }

    public function deleteLookupType(LookupType $lookupType)
    {
        $lookupType->delete();
        return $lookupType;
    }
}
