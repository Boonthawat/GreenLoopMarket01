<?php
require 'init.php';
require 'lib/marketplace.php';

market_require_user();
go('shop.php?cart=1');
