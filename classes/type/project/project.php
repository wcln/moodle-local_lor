<?php

namespace local_lor\type\project;

use coding_exception;
use context_system;
use core\plugininfo\repository;
use dml_exception;
use html_writer;
use local_lor\helper;
use local_lor\item\data;
use local_lor\item\item;
use local_lor\type\type;
use moodle_url;


class project
{
    use type;

    const PROPERTIES = ['pdf', 'document'];

    /** @var string This is where the project files will be stored in the filesystem */
    const STORAGE_DIR = 'projects';

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
        $item_data         = data::get_item_data($itemid);
        $pdf_filename      = $item_data['pdf'];
        $document_filename = $item_data['document'];

        $html = html_writer::tag('embed', '', [
            'src'    => \local_lor\repository::get_file_url(self::get_path_to_project_file($pdf_filename),
                $pdf_filename),
            'width'  => '100%',
            'height' => '100%',
        ]);

        // A 'Download Word document' button
//        $html .= html_writer::tag('a', get_string('download_document', 'lortype_project'), [
//            'class'    => 'btn btn-default',
//            'download' => $document_filename,
//            'href'     => \local_lor\repository::get_file_url(self::get_path_to_project_file($document_filename),
//                $document_filename),
//        ]);

        return $html;
    }

    /**
     * Add type specific elements to the form
     *
     * @param $item_form
     * @param  null  $item  Item is only sent if we are editing
     *
     * @throws coding_exception
     */
    public static function add_to_form(&$item_form, $item = null)
    {
//        $draftitemid = file_get_submitted_draft_itemid('pdf');
//        file_prepare_draft_area($draftitemid, $context->id, 'local_lor', 'preview_image', $item->id);
//        $item->image = $draftitemid;

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

    /**
     * Save the project PDF and .docx to the filesystem
     *
     * - Specify class constant STORAGE_DIR where files are saved
     * - Specify class constant FILENAME_PREFIX to change how the files are named
     *      - Default is WCLN_Project_{Item_ID}.png/.docx
     *
     * @param  int  $itemid
     *
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    private static function process_files(int $itemid)
    {
        $item = item::get($itemid);

        \local_lor\repository::save_to_repository([
            [
                'name'     => 'pdf',
                'filepath' => self::get_path_to_project_file(\local_lor\repository::format_filename("$item->name.pdf")),
            ],
            [
                'name'     => 'document',
                'filepath' => self::get_path_to_project_file(\local_lor\repository::format_filename("$item->name.docx")),
            ],
        ]);

        // TODO we need to return the filepaths so those can be stored in the database correctly
    }

    public static function create($itemid, $data, &$form = null)
    {
        global $DB;

        $success = true;

        $results = self::process_files($itemid);

        foreach (self::PROPERTIES as $property) {
            $record = [
                'itemid' => $itemid,
                'name'   => $property,
                'value'  => $results[$property],
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

        $results = self::process_files($itemid);

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

    /**
     * Get the path to the project file relative to the repository root
     *
     * @param $filename
     *
     * @return string
     */
    private static function get_path_to_project_file($filename)
    {
        return self::STORAGE_DIR."/".$filename;
    }


}
