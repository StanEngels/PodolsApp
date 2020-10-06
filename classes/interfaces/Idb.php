<?php

namespace interfaces;

interface Idb
{
    public function actionQuery(string $sql);

    public function selectQuery(string $sql);
}
