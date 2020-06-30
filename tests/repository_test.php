<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Class local_lor_repository_testcase
 *
 * @group local_lor
 */
class local_lor_repository_testcase extends advanced_testcase {
    public function test_format_filename() {
        $test_filenames = [
            [
                'raw' => 'Rounding to the nearest thousand.pdf',
                'expected' => 'rounding to the nearest thousand.pdf'
            ],
            [
                'raw' => 'The Leaning Tower of Lire (Hands-on). How can we easily locate an object\'s center of mass? Can you build a large overhang with blocks?.pdf',
                'expected' => 'the leaning tower of lire handson how can we easily locate an objects center of mass can you build a large overhang with blocks.pdf'
            ],
            [
                'raw' => 'How did the Yup’ik people of the north use patterns with repeating polygons to make decorative borders on their winter parkas?.docx',
                'expected' => 'how did the yupik people of the north use patterns with repeating polygons to make decorative borders on their winter parkas.docx'
            ],
            [
                'raw' => 'WCLN_Project_4886.pdf',
                'expected' => 'wcln_project_4886.pdf',
            ],
            [
                'raw' => 'test__!!^&...$.pdf',
                'expected' => 'test__.pdf'
            ]
        ];

        foreach ($test_filenames as $filename) {
            $this->assertEquals($filename['expected'], \local_lor\repository::format_filename($filename['raw']));
        }
    }
}
