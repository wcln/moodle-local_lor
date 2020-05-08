<?php

namespace local_lor\type\project;

use local_lor\helper;
use local_lor\item\data;
use local_lor\item\item;
use local_lor\type\type;


class project
{
    use type;

    const PROPERTIES = ['pdf', 'document'];

    /** @var string This is where the project files will be stored in the filesystem */
    const STORAGE_DIR = '/_LOR/projects/';

    /** @var string The prefix to append before the item ID and file type when saving files to the filesystem */
    const FILENAME_PREFIX = 'WCLN_Project_';

    public static function get_name()
    {
        return get_string('type_name', 'lortype_project');
    }

    public static function get_embed_html($itemid)
    {
        global $CFG;

        $item   = item::get($itemid);
        $topics = helper::implode_format($item->topics);

        $pdf = $CFG->wwwroot.self::STORAGE_DIR.$item->data['pdf'];
        $img = item::get_image_url($itemid);

        // TODO: this should be rendered using a template at some point. However, Moodle does not allow template rendering in subplugins...
        return '
            <table align="center" border="1" style="width: 600px;">
              <tbody>
                <tr>
                  <td width="200px"><a href="'.$pdf.'"><img src="'.$img.'" width="200"
                                                                      height="150"/></a></td>
                  <td><b><span style="background-color: transparent; color: #7d9fd3; font-size: 16px;">'
               .$item->data['name'].'</span><br/></b><br/><span
                              style="color: #c8c8c8;">Topics: '.$topics.'</span></td>
                </tr>
              </tbody>
            </table>
        ';
    }

    public static function get_display_html($itemid)
    {
        return 'todo';
    }

    public static function add_to_form(&$item_form)
    {
        // PDF (.pdf)
        $item_form->addElement('filemanager', 'pdf', get_string('pdf', 'lortype_project'), null,
            ['maxfiles' => 1, 'accepted_types' => ['.pdf']]);
        $item_form->addHelpButton('pdf', 'pdf', 'lortype_project');
        $item_form->addRule('pdf', get_string('required'), 'required');

        // Document (.docx)
        $item_form->addElement('filemanager', 'document', get_string('document', 'lortype_project'), null,
            ['maxfiles' => 1, 'accepted_types' => ['.docx']]);
        $item_form->addHelpButton('document', 'document', 'lortype_project');
        $item_form->addRule('document', get_string('required'), 'required');
    }

    private static function process_files(int $itemid, &$form)
    {
        global $CFG;

        $pdf_filename      = self::FILENAME_PREFIX.$itemid.'.pdf';
        $document_filename = self::FILENAME_PREFIX.$itemid.'.docx';

        return [
            'pdf'      => [
                'filename' => $pdf_filename,
                'success'  => $form->save_file('pdf', $CFG->dirroot.self::STORAGE_DIR.$pdf_filename, true),
            ],
            'document' => [
                'filename' => $document_filename,
                'success'  => $form->save_file('document', $CFG->dirroot.self::STORAGE_DIR.$document_filename, true),
            ],
        ];
    }

    public static function create($itemid, $data, &$form = null)
    {
        global $DB;

        $success = true;

        $results = self::process_files($itemid, $form);

        foreach (self::PROPERTIES as $property) {
            $record = [
                'itemid' => $itemid,
                'name'   => $property,
                'value'  => $results[$property]['filename'],
            ];

            $success = $success
                       && $DB->insert_record(
                    data::TABLE,
                    (object)$record
                );
        }

        return $success;
    }

    public static function update($itemid, $data, &$form = null)
    {
        global $DB;

        $success = true;

        $results = self::process_files($itemid, $form);

        foreach (self::PROPERTIES as $property) {
            if ($existing_record = $DB->get_record_select(
                data::TABLE,
                "itemid = :itemid AND name LIKE :name",
                [
                    'itemid' => $itemid,
                    'name'   => $property,
                ]
            )
            ) {
                $record = [
                    'id'     => $existing_record->id,
                    'itemid' => $itemid,
                    'name'   => $property,
                    'value'  => $results[$property]['filename'],
                ];

                $success = $success
                           && $DB->update_record(
                        data::TABLE,
                        (object)$record
                    );
            }
        }

        return $success;
    }


}
