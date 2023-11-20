<?php

namespace App;

enum InfraLevel: string {
    case Uni = 'uni';
    case Org = 'org';
    case Fnd = 'fnd';
    case Env = 'env';
}