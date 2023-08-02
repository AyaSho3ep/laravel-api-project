#!/bin/bash
php artisan serve & concurrently "node socket.js"
