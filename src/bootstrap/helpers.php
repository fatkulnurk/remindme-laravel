<?php

if (!function_exists('to_timestamp_second')) {
    function to_timestamp_second(int|float|string $timestamp): int|string
    {
        return \Illuminate\Support\Str::length($timestamp) > 10 ? round($timestamp / 1000) : $timestamp;
    }
}
