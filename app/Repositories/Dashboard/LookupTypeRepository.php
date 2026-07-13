<?php

namespace App\Repositories\Dashboard;

use App\Models\LookupType;
use Illuminate\Support\Facades\DB;

class LookupTypeRepository
{
    public function getLookupTypeList()
    {
        return LookupType::with('values')->get();
    }

    public function getLookupTypeDetails($id)
    {
        return LookupType::with('values')->findOrFail($id);
    }

    public function addNewLookupType($lookup_type_request)
    {
        return LookupType::create($lookup_type_request);
    }

    public function updateLookupType(LookupType $lookupType, $lookup_type_request)
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
