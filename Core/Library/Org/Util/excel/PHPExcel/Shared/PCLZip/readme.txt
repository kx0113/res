// --------------------------------------------------------------------------------
// PclZip 2.8.2 - readme.txt
// --------------------------------------------------------------------------------
// License GNU/LGPL - August 2009
// Vincent Blavet - vincent@phpconcept.net
// http://www.phpconcept.net
// --------------------------------------------------------------------------------
// $Id: readme.txt,v 1.60 2009/09/30 20:35:21 vblavet Exp $
// --------------------------------------------------------------------------------



0 - Sommaire
============
    1 - Introduction
    2 - What's new
    3 - Corrected bugs
    4 - Known bugs or limitations
    5 - License
    6 - Warning
    7 - Documentation
    8 - Author
    9 - Contribute

1 - Introduction
================

  PclZip is a library that allow you to manage a Zip archive.

  Full documentation about PclZip can be found here : http://www.phpconcept.net/pclzip

2 - What's new
==============

  Version 2.8.2 :
    - PCLZIP_CB_PRE_EXTRACT and PCLZIP_CB_POST_EXTRACT are now supported with 
      extraction as a string (PCLZIP_OPT_EXTRACT_AS_STRING). The string
      can also be modified in the post-extract call back.
    **Bugs correction :
    - PCLZIP_OPT_REMOVE_ALL_PATH was not working correctly    
    - Remove use of eval() and do direct call to callback functions
    - Correct support of 64bits systems (Thanks to WordPress team)

  Version 2.8.1 :
    - Move option PCLZIP_OPT_BY_EREG to PCLZIP_OPT_BY_PREG because ereg() is
      deprecated in PHP 5.3. When using option PCLZIP_OPT_BY_EREG, PclZip will
      automatically replace it by PCLZIP_OPT_BY_PREG.
  
  Version 2.8 :
    - Improve extraction of zip archive for large files by using temporary files
      This feature is working like the one defined in r2.7.
      Options are renamed : PCLZIP_OPT_TEMP_FILE_ON, PCLZIP_OPT_TEMP_FILE_OFF,
      PCLZIP_OPT_TEMP_FILE_THRESHOLD
    - Add a ratio constant PCLZIP_TEMPORARY_FILE_RATIO to configure the auto
      sense of temporary file use.
    - Bug correction : Reduce filepath in returned file list to remove ennoying
      './/' preambule in file path.

  Version 2.7 :
    - Improve creation of zip archive for large files :
      PclZip will now autosense the configured memory and use temporary files
      when large file is suspected.
      This feature can also ne triggered by manual options in create() and add()
      methods. 'PCLZIP_OPT_ADD_TEMP_FILE_ON' force the use of temporary files,
      'PCLZIP_OPT_ADD_TEMP_FILE_OFF' disable the autosense technic, 
      'PCLZIP_OPT_ADD_TEMP_FILE_THRESHOLD' allow for configuration of a size
      threshold to use temporary files.
      Using "temporary files" rather than "memory" might take more time, but
      might give the ability to zip very large files :
      Tested on my win laptop with a 88Mo file :
        Zip "in-memory" : 18sec (max_execution_time=30, memory_limit=180Mo)
        Zip "tmporary-files" : 23sec (max_execution_time=30, memory_limit=30Mo)
    - Replace use of mktime() by time() to limit the E_STRICT error messages.
    - Bug correction : When adding files with full windows path (drive letter)
      PclZip is now working. Before, if the drive letter is not the default
      path, PclZip was not able to add the file.

  Version 2.6 :
    - Code optimisation
    - New attributes PCLZIP_ATT_FILE_COMMENT gives the ability to
      add a comment for a specific file. (Don't really know if this is usefull)
    - New attribute PCLZIP_ATT_FILE_CONTENT gives the ability to add a string 
      as a file.
    - New attribute PCLZIP_ATT_FILE_MTIME modify the timestamp associated with
      a file.
    - Correct a bug. Files archived with a timestamp with 0h0m0s were extracted
      with current time
    - Add CRC value in the informations returned back for each file after an
      action.
    - Add missing closedir() statement.
    - When adding a folder, and removing the path of this folder, files were
      incorrectly added with a '/' at the beginning. Which means files are 
      related to root in unix systems. Corrected.
    - Add conditional if before constant definition. This will allow users
      to redefine constants without changing the file, and then improve
      upgrade of pclzip code for new versions.
  
  Version 2.5 :
    - Introduce the ability to add file/folder with individual properties (file descriptor).
      This gives for example the ability to change the filename of a zipped file.
      . Able to add files individually
      . Able to change full name
      . Able to change short name
      . Compatible with global options
    - New attributes : PCLZIP_ATT_FILE_NAME, PCLZIP_ATT_FILE_NEW_SHORT_NAME, PCLZIP_ATT_FILE_NEW_FULL_NAME
    - New error code : PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE
    - Add a security control feature. PclZip can extract any file in any folder
      of a system. People may use this to upload a zip file and try to override
      a system file. The PCLZIP_OPT_EXTRACT_DIR_RESTRICTION will give the
      ability to forgive any directory transversal behavior.
    - New PCLZIP_OPT_EXTRACT_DIR_RESTRICTION : check extraction path
    - New error code : PCLZIP_ERR_DIRECTORY_RESTRICTION
    - Modification in PclZipUtilPathInclusion() : dir and path beginning with ./ will be prepend
      by current path (getcwd())
  
  Version 2.4 :
    - Code improvment : try to speed up the code by removing unusefull call to pack()
    - Correct bug in delete() : delete() should be called with no argument. This was not
      the case in 2.3. This is corrected in 2.4.
    - Correct a bug in path_inclusion function. When the path has several '../../', the
      result was bad.
    - Add a check for magic_quotes_runtime configuration. If enabled, PclZip will 
      disable it while working and det it back to its original value.
      This resolve a lots of bad formated archive errors.
    - Bug correction : PclZip now correctly unzip file in some specific situation,
      when compressed content has same size as uncompressed content.
    - Bug correction : When selecting option 'PCLZIP_OPT_REMOVE_ALL_PATH', 
      directories are not any more created.
    - Code improvment : correct unclosed opendir(), better handling of . and .. in
      loops.


  Version 2.3 :
    - Correct a bug with PHP5 : affecting the value 0xFE49FFE0 to a variable does not
      give the same result in PHP4 and PHP5 ....

  Version 2.2 :
    - Try development of PCLZIP_OPT_CRYPT .....
      However this becomes to a stop. To crypt/decrypt I need to multiply 2 long integers,
      the result (greater than a long) is not supported by PHP. Even the use of bcmath
      functions does not help. I did not find yet a solution ...;
    - Add missing '/' at end of directory entries
    - Check is a file is encrypted or not. Returns status 'unsupported_encryption' and/or
      error code PCLZIP_ERR_UNSUPPORTED_ENCRYPTION.
    - Corrected : Bad "version need to extract" field in local file header
    - Add private method privCheckFileHeaders() in order to check local and central
      file headers. PclZip is now supporting purpose bit flag bit 3. Purpose bit flag bit 3 gives
      the ability to have a local file header without size, compressed size and crc filled.
    - Add a generic status 'error' for file status
    - Add control of compression type. PclZip only support deflate compression method.
      Before v2.2, PclZip does not check the compression method used in an archive while
      extracting. With v2.2 PclZip returns a new error status for a file using an unsupported
      compression method. New status is "unsupported_compression". New error code is
      PCLZIP_ERR_UNSUPPORTED_COMPRESSION.
    - Add optional attribute PCLZIP_OPT_STOP_ON_ERROR. This will stop the extract of files
      when errors like 'a folder with same name exists' or 'a newer file exists' or
      'a write protected file' exists, rather than set a status for the concerning file
      and resume the extract of the zip.
    - Add optional attribute PCLZIP_OPT_REPLACE_NEWER. This will force, during an extract' the
      replacement of the file, even if a  newer version of the file exists.
      Note that today if a file with the same name already exists but is older it will be
      replaced by the extracted one.
    - Improve PclZipUtilOption()
    - Support of zip archive with trailing bytes. Before 2.2, PclZip checks that the central
      directory structure is the last data in the archive. Crypt encryption/decryption of
      zip archive put trailing 0 bytes after decryption. PclZip is now supporting this.

  Version 2.1 :
    - Add the ability to abort the extraction by using a user callback function.
      The user can now return the value '2' in its callback which indicates to stop the
      extraction. For a pre call-back extract is stopped before the extration of the current
      file. For a post call back, the extraction is stopped after.
    - Add the ability to extract a file (or several files) directly in the standard output.
      This is done by the new parameter PCLZIP_OPT_EXTRACT_IN_OUTPUT with method extract().
    - Add support for parameters PCLZIP_OPT_COMMENT, PCLZIP_OPT_ADD_COMMENT,
      PCLZIP_OPT_PREPEND_COMMENT. This will create, replace, add, or prepend comments
      in the zip archive.
    - When merging two archives, the comments are not any more lost, but merged, with a 
      blank space separator.
    - Corrected bug : Files are not deleted when all files are asked to be deleted.
    - Corrected bug : Folders with name '0' made PclZip to abort the create or add feature.


  Version 2.0 :
    ***** Warning : Some new features may break the backward compatibility for your scripts.
                    Please carefully read the readme file.
    - Add the ability to delete by Index, name and regular expression. This feature is 
      performed by the method delete(), which uses the optional parameters
      PCLZIP_OPT_BY_INDEX, PCLZIP_OPT_BY_NAME, PCLZIP_OPT_BY_EREG or PCLZIP_OPT_BY_PREG.
    - Add the ability to extract by regular expression. To extract by regexp you must use the method
      extract(), with the option PCLZIP_OP