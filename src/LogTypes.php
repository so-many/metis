<?php

namespace SoManyProblems\Metis;

enum LogTypes: String {
    case emergency = 'emergency';
    case alert = 'alert';
    case critical = 'critical';
    case error = 'error';
    case warning = 'warning';
    case notice = 'notice';
    case info = 'info';
    case debug = 'debug';
}