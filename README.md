Massive work-in-progress, please contact me if you'd like to contribute.

Notes:
 - Needs a deploy script to check permissions, create settings files  etc.
 - (For now there's a simple install script at http://your.site/install.php -- it works fine if it works, but does nothing clever about checking requirements)
 - Currently includes FPDF and FPDI, should switch to pulling them separately as part of deploy
 - Currently has loads of workplace-specific content
 - Code has got uber messy due to rapid hacks, needs consolidation. (Switch to OOP?)
 
 field types:
 
 - **Normal**: Displays as-is using positioning and formatting as given
 - **Data** - Takes input but not directly displayed -- can be used in "Wrapper" type fields. Ignores all formatting in favour of that of the "Wrapper".
 - **Wrapper**: Not editable, pulls text from other field values to fill in tokens. Tokens take the form {X} where X is the id of the related field (**Data** or **Normal**).
