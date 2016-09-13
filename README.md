A proof-of-concept turned into production code -- still quite raw and very much a work-in-progress, please contact me if you'd like assistance or if you're interested in contributing.

Notes:
 - Needs a solid deployment script to check permissions, create settings files  etc.
 - (For now there's a simple install script at http://your.site/install.php -- it works fine if it works, but does nothing clever about checking any requirements)
 - Currently includes FPDF and FPDI, should switch to pulling them separately as part of deployment
 - Currently has residual workplace-specific content
 - Code has got uber messy due to rapid hacks, needs proper consolidation/refactoring. (Switch to OOP?)
 
Field types:
 
 - **Normal**: Displays as-is using positioning and formatting as given
 - **Data** - Takes input but not directly displayed -- can be used in "Wrapper" type fields. Ignores all formatting in favour of that of the "Wrapper".
 - **Wrapper**: Not editable, pulls text from other field values to fill in tokens. Tokens take the form {X} where X is the id of the related field (**Data** or **Normal**).
