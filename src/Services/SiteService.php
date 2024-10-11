<?php

namespace Morethingsdigital\StatamicNextjs\Services;

use Statamic\Facades\Site as SiteFacade;
use Statamic\Sites\Site;

class SiteService
{

  public function __construct() {}



  public function currenteSite(): Site
  {
    if (SiteFacade::hasMultiple()) {
      SiteFacade::setCurrent(request()->query('site') ?? request()->session()->get('statamic.cp.selected-site') ??  SiteFacade::default()->handle());
    }


    return SiteFacade::current();
  }
}
