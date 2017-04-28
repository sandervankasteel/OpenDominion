<?php

namespace OpenDominion\Http\Controllers\Dominion;

use DB;
use OpenDominion\Calculators\Dominion\LandCalculator;
use OpenDominion\Calculators\NetworthCalculator;
use OpenDominion\Models\Realm;

class RealmController extends AbstractDominionController
{
    public function getRealm(Realm $realm = null)
    {
        /** @var LandCalculator $landCalculator */
        $landCalculator = resolve(LandCalculator::class);

        /** @var NetworthCalculator $networthCalculator */
        $networthCalculator = resolve(NetworthCalculator::class);

        if (($realm === null) || !$realm->exists) {
            $realm = $this->getSelectedDominion()->realm;
        }

        $dominions = $realm->dominions()/*->with('race')*/->orderBy('networth', 'desc')->get();

        // Todo: optimize this hacky hacky stuff
        $prevRealm = DB::table('realms')
            ->where('number', '<', $realm->number)
            ->orderBy('number', 'desc')
            ->limit(1)
            ->first();

        $nextRealm = DB::table('realms')
            ->where('number', '>', $realm->number)
            ->orderBy('number', 'asc')
            ->limit(1)
            ->first();

        return view('pages.dominion.realm', compact(
            'landCalculator',
            'networthCalculator',
            'realm',
            'dominions',
            'prevRealm',
            'nextRealm'
        ));
    }
}