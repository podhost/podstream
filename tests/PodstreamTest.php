<?php
namespace Podhost\Podstream\Tests;

use Podhost\Podstream\Podstream;

class PodstreamTest extends OrchestraTestCase
{
    public function test_roles_can_be_registered()
    {
        Podstream::$permissions = [];
        Podstream::$roles = [];

        Podstream::role('admin', __('Administrator'), [
            'podcast:update',
            'manager:create',
            'manager:update',
            'episode:create',
            'episode:update',
            'episode:delete',
        ])->description(__('Administrator users can perform any action.'));

        Podstream::role('manager', __('Manager'), [
            'episode:create',
            'episode:update',
        ])->description(__('Editor users have the ability to read, create, and update.'));

        Podstream::role('analytics', __('Analytics'), [
        ])->description(__('Analytics users have the ability to read podcast analytics only.'));


        $this->assertTrue(Podstream::hasPermissions());

        $this->assertEquals([
            'create',
            'delete',
            'read',
            'update',
        ], Podstream::$permissions);
    }
}
