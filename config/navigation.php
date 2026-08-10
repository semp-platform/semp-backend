<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ICT & Publication
    |--------------------------------------------------------------------------
    */

    'ict' => [

        'title' => 'ICT & Publication',
        'description' => 'Technical processing, data verification and publication readiness.',

        'dashboard' => [
            'label' => 'ICT Dashboard',
            'route' => 'staff.ict.dashboard',
        ],

        'sections' => [

            [
                'label' => 'Nomination Processing',
                'items' => [
                    [
                        'label' => 'Nomination Batches',
                        'route' => 'staff.ict.nomination-batches.index',
                    ],
                    [
                        'label' => 'Candidate Records',
                        'route' => null,
                    ],
                    [
                        'label' => 'Document & Data Verification',
                        'route' => null,
                    ],
                ],
            ],

            [
                'label' => 'Publication',
                'items' => [
                    [
                        'label' => 'Publication Readiness',
                        'route' => null,
                    ],
                    [
                        'label' => 'Approved Batches',
                        'route' => null,
                    ],
                    [
                        'label' => 'Publication / Export',
                        'route' => null,
                    ],
                ],
            ],

            [
                'label' => 'Administration',
                'items' => [
                    [
                        'label' => 'Audit Trail',
                        'route' => null,
                    ],
                ],
            ],

        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Election & Party Monitoring
    |--------------------------------------------------------------------------
    */

    'epm' => [

        'title' => 'Election & Party Monitoring',
        'description' => 'Primary monitoring, reconciliation and nomination verification.',

        'dashboard' => [
            'label' => 'EPM Dashboard',
            'route' => 'staff.epm.nominations.index',
        ],

        'sections' => [

            [
                'label' => 'Primary Monitoring',
                'items' => [
                    [
                        'label' => 'Primary Monitoring',
                        'route' => null,
                    ],
                    [
                        'label' => 'Registered Parties',
                        'route' => null,
                    ],
                    [
                        'label' => 'Primary Notices',
                        'route' => null,
                    ],
                    [
                        'label' => 'Field Monitoring Reports',
                        'route' => null,
                    ],
                ],
            ],

            [
                'label' => 'Nomination Reconciliation',
                'items' => [
                    [
                        'label' => 'Incoming Candidate Submissions',
                        'route' => 'staff.epm.nominations.index',
                    ],
                    [
                        'label' => 'Nomination Reconciliation',
                        'route' => null,
                    ],
                    [
                        'label' => 'Discrepancy / Mismatch Queue',
                        'route' => null,
                    ],
                ],
            ],

            [
                'label' => 'Workflow',
                'items' => [
                    [
                        'label' => 'Department Handoff',
                        'route' => null,
                    ],
                ],
            ],

        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Legal & Compliance
    |--------------------------------------------------------------------------
    */

    'legal' => [

        'title' => 'Legal & Compliance',
        'description' => 'Qualification, affidavit, litigation and statutory review.',

        'dashboard' => [
            'label' => 'Legal Dashboard',
            'route' => 'staff.legal.nominations.index',
        ],

        'sections' => [

            [
                'label' => 'Qualification Review',
                'items' => [
                    [
                        'label' => 'Qualification Vetting',
                        'route' => 'staff.legal.nominations.index',
                    ],
                    [
                        'label' => 'Affidavit Review',
                        'route' => null,
                    ],
                    [
                        'label' => 'Educational / Age Qualification',
                        'route' => null,
                    ],
                ],
            ],

            [
                'label' => 'Legal Matters',
                'items' => [
                    [
                        'label' => 'Withdrawal & Substitution',
                        'route' => null,
                    ],
                    [
                        'label' => 'Court Orders',
                        'route' => null,
                    ],
                    [
                        'label' => 'Contested Nominations',
                        'route' => null,
                    ],
                    [
                        'label' => 'Legal Documents',
                        'route' => null,
                    ],
                ],
            ],

            [
                'label' => 'Workflow',
                'items' => [
                    [
                        'label' => 'Department Handoff',
                        'route' => null,
                    ],
                ],
            ],

        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Commissioner / Executive
    |--------------------------------------------------------------------------
    */

    'commissioner' => [

        'title' => 'Commission / Executive',
        'description' => 'Final review, decision and executive authorization.',

        'dashboard' => [
            'label' => 'Executive Dashboard',
            'route' => 'staff.commissioner.nominations.index',
        ],

        'sections' => [

            [
                'label' => 'Executive Overview',
                'items' => [
                    [
                        'label' => 'Election Readiness',
                        'route' => null,
                    ],
                    [
                        'label' => 'Candidate / Batch Overview',
                        'route' => null,
                    ],
                ],
            ],

            [
                'label' => 'Final Decision',
                'items' => [
                    [
                        'label' => 'Pending Final Decisions',
                        'route' => 'staff.commissioner.nominations.index',
                    ],
                    [
                        'label' => 'EPM & Legal Review Summary',
                        'route' => null,
                    ],
                    [
                        'label' => 'Commissioner Decision Register',
                        'route' => null,
                    ],
                ],
            ],

            [
                'label' => 'Audit & Handoff',
                'items' => [
                    [
                        'label' => 'Audit / Override Log',
                        'route' => null,
                    ],
                    [
                        'label' => 'Department Handoff',
                        'route' => null,
                    ],
                ],
            ],

        ],
    ],

];
