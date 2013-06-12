Massive work-in-progress, please contact me if you'd like to contribute.

Notes:
 - Needs a deploy script to check permissions, create settings files  etc.
 - Currently includes FPDF and FPDI, should switch to pulling them seperatly as part of deploy
 - Currently has loads workplace-specific content
 - Code has got uber messy due to rapid hacks, needs consolidation. OOP?
 
 field types:
 
 Normal
 Data - needs input but not directly displayed
 Wrapper - pulls text from other field values to fill in tokens. Tokens take the form {X} where X is the id of the related field
