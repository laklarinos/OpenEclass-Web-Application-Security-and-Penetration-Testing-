## Open eClass 2.3

Το repository αυτό περιέχει μια __παλιά και μη ασφαλή__ έκδοση του eclass.
Προορίζεται για χρήση στα πλαίσια του μαθήματος
[Προστασία & Ασφάλεια Υπολογιστικών Συστημάτων (ΥΣ13)](https://ys13.chatzi.org/), __μην τη
χρησιμοποιήσετε για κάνενα άλλο σκοπό__.


### Χρήση μέσω docker
```
# create and start (the first run takes time to build the image)
docker-compose up -d

# stop/restart
docker-compose stop
docker-compose start

# stop and remove
docker-compose down -v
```

To site είναι διαθέσιμο στο http://localhost:8001/. Την πρώτη φορά θα πρέπει να τρέξετε τον οδηγό εγκατάστασης.


### Ρυθμίσεις eclass

Στο οδηγό εγκατάστασης του eclass, χρησιμοποιήστε __οπωσδήποτε__ τις παρακάτω ρυθμίσεις:

- Ρυθμίσεις της MySQL
  - Εξυπηρέτης Βάσης Δεδομένων: `db`
  - Όνομα Χρήστη για τη Βάση Δεδομένων: `root`
  - Συνθηματικό για τη Βάση Δεδομένων: `1234`
- Ρυθμίσεις συστήματος
  - URL του Open eClass : `http://localhost:8001/` (προσοχή στο τελικό `/`)
  - Όνομα Χρήστη του Διαχειριστή : `drunkadmin`

Αν κάνετε κάποιο λάθος στις ρυθμίσεις, ή για οποιοδήποτε λόγο θέλετε να ρυθμίσετε
το openeclass από την αρχή, διαγράψτε το directory, `openeclass/config` και ο
οδηγός εγκατάστασης θα τρέξει ξανά.

## 2023 Project 1

Εκφώνηση: https://ys13.chatzi.org/assets/projects/project1.pdf


### Μέλη ομάδας

- 1115201800090, Λακές Αθανάσιος
- 1115201800280, Πολυξένη Κόλλια

### Report

Συμπληρώστε εδώ __ένα report__ που
- Να εξηγεί τι είδους αλλαγές κάνατε στον κώδικα για να προστατέψετε το site σας (από την κάθε επίθεση).
### CSRF
Το site του openeclass ήταν αρκετά ευπαθές σε επιθέσεις CSRF. Πιό συγκεκριμένα, για την επίτευξη της αντιμετώπισης μίας τέτοια επίθεσης σε κάποιο module του site είναι απαραίτητη η χρήση των token. Η λογική είναι πως κάθε φορά που υπάρχει κάποιο POST ή κάποιο GET request που θα έκανε ο χρήστης κατά την χρήση του site αυτά θα πρέπει να είμαι προστατευμένα με CSRF token προκειμένου να εξασφαλισθεί πως η ενέργεια γίνεται μέσα από το ίδιο το site και συνεπώς, λογικά, αυτός που την κάνει είναι ο χρήστης εν γνώσει του. Εκεί στοχεύουν και οι επιθέσεις CSRF δηλαδή έχοντας οδηγήσει τον χρήστη σε μία ιστοσελίδα την οποία ο κακόβουλος χρήστης έχει δημιουργήσει, παραποιούν φαινομενικά απλές και προφανείς ενέργειες τύπου "κάνε κλικ εδώ" με ενέργειες που σκοπό έχουν είτε να αποκλέψουν στοιχεία σημαντικά από κάποιον χρήστη είτε να τον αναγκάσουν να εκτελέσει μία ενέργεια την οποία δεν γνωρίζει οτι κάνει και που πιθανότατα δεν θα ήθελα να κάνει.

Για να οργανώσουμε μία άμυνα πάνω στις επιθέσεις **csrf** ξεκινήσαμε να ψάχνουμε το site για όλες εκείνες τις **post** ή **get** ενέργειες. Αρχικά επικεντρωθήκαμε στα ενεργοποιημένα modules του καθήματος "Ανταλλαγή αρχείων", "Περιχές Συζητήσεων", "Εργασίες" και "Τηλεσυνεργασία" και προσπαθήσαμε να προσθέσουμε στοιχεία token στους κώδικες προκειμένου να πετύχουμε την ακόλουθη προσέγγιση. 

## Προσέγγιση anti-CSRF
Έπειτα από κατανόηση των εννοιών έγινε προφανές πως πρέπει να δημιουργούμε ένα τυχαίο, απρόβλεπτο token το οποίο και θα έπρεπε να προσθέτουμε είτε ως ένα πεδίο μιας φόρμας που αποσκοπεί σε ένα POST request είτε προσθέτοντας το σε URL όταν μιλάμε για GET request. Αυτό το token αποφασίσαμε να γίνεται generate όταν ο χρήστης κάνει SUBMIT και αντίστοιχα θα προστίθεται στη φόρμα/URL λίγο πριν την αποστολή του αιτήματος στη βάση.

Όταν φτάσει το αίτημα στην βάση, εκεί θα εκτελείτε ένα έλεγχος που αποτελεί και την ουσία της anti-CSRF τακτικής. Ο έλεγχος αυτός κοιτάζει αν είναι set τα απαραίτητα session variables (όταν μιλάμε για POST request) 
```
if (! isset($_POST['CSRFName']) or ! isset($_POST['CSRFToken']))
```
και αν ναι τότε προχωράει στην σύγκριση του δωσμένου token με εκείνου που έχει καταχωρηθεί στην μεταβλητή $_SESSION['frm_name']. Ένα σημαντικό χαρακτηριστικό της συνάρτησης που κάνει generate το token είναι οτι μαζί με την τυχαία τιμή του, δημιουργεί και μία μεταβήτή session με ένα τυχαίο όνομα
```
$name = "CSRFGuard_".mt_rand(0,mt_getrandmax());
```
αυτό είναι πολύ σημαντικό γιατί προσδίδει ένα ακόμη layer τυχαιότητας. Αυτό, σε συνδυασμό με το γεγονός οτι τα token δεν μένουν ποτέ τα ίδια ούτε καν στην ίδια σελίδα και την ίδια φόρμα αλλά κάθε φορά που μια τέτοια σελίδα φοτώνεται δημιουργούνται νέα, δυσκολεύουν πολύ κάποια csrf επίθεση. Στη συνέχεια ο δεύτερος έλεγχος θα αποφασίσει αν το token που αναλογεί στη συγκεκριμένη μεταβλητή είναι ίδιο με εκείνο που έχει γίνει inject στη φόρμα και αν όλα πάνε καλά θα προχωρήσει στις ανάλογες διαδικασίες στη βάση. Αντίστοιχα αν δεν πετύχει κάποιος έλεγχος έχουν γραφτεί μηνύματα λάθους που όμως δεν αναφέρουν σφάλμα λόγω csrf επίθεσης. 

Πιο συγκεκριμένα θα παρατηρήσετε anti-CSRF κώδικες στα ακόλουθα αρχεία:

admin/adminannouncements.php
admin/change_user.php
admin/eclassconf.php
admin/edituser.php
admin/multireguser.php
admin/password.php
course_info/infocours.php
create_course/create_course.php
forum_admin/forum_admin.php
group/group_creation.php
group/group_edit.php
group/group_email.php
phpbb/editpost.php
phpbb/newtopic.php
profile/password.php
profile/profile.php
units/info.php
work/work.php
admin/unreguser.php (GET request)
forum_admin/forum_admin.php (GET request)
work/work.php (GET request)

### RFI
Δεν υλοποιήθηκε καμία άμυνα για rfi επιθέσεις.

- Να εξηγεί τι είδους επιθέσεις δοκιμάσατε στο αντίπαλο site και αν αυτές πέτυχαν.

### SQLI και XSS

Τα SQL Injection και XSS έχουν υλοποιηθεί στο ίδιο αρχείο καθώς είχαν μερικά κοινά στοιχεία. Πρόκειται για τα αρχεία xss_sql_asgnsub, xss_sql_groupdoc, xss_sql_topics, xss_sql_topics_posts που έχουν εφαρμοστεί στα εξής modules του eclass 1. assignment submission 2. group documents 3. topics 4. topics posts. Ο λόγος που τα εφαρμόσαμε σε αυτά τα modules είναι γιατί είχαν την περισσότερη διεπαφή με τον user και που θα μπορούσε ο αντίπαλος να στείλει κάποιο κακόβουλο μήνυμα ή αρχείο. Όσον αφορά το κομμάτι του κώδικα και την υλοποίησή του χρειάστηκε να κάνουμε validation τα inputs του χρήστη και στις 2 περιπτώσεις και προσθέσαμε και το κομμάτι του encoding για το XSS και του sanitization για το SQL Injection, όπως αυτά χρειαζόντουσαν στο κάθε module.

### Deface
Προκειμένου να αποκτήσουμε πρόσβαση στο site μέσω του λογαριασμού του admin, δημιουργήσαμε το puppies/cv. Το directory αυτό περιέχει 2 php αρχεία cookie.php και index.php. Το index.php, όταν κάνει load 

Παρατηρήσαμε πως modules/phpbb/reply.php δεν ήταν προστατευμένο από xss attack, αυτό σημαίνει πως μπορούσε κανείς να εκτελέσει javascript κώδικα μέσα σε αυτό (όπως και σε πολλά άλλα modules). Συνεπώς μας έδωσε έναν καλό μπούσουλα να δουλέψουμε. Το σκεπτικό ήταν να καταφέρουμε να κλέψουμε το cookie του admin ώστε να συνδεθούμε και αυτό μπορούσαμε να το καταφέρουμε αν κάπως αναγκάζαμε τον χρήστη να στείλει ένα get request dragonfly.puppies.chatzi.org/cv (θα αναλυθεί σε λιγάκι πως θα δούλευε αυτό) και όλο αυτό θέλαμε να το κρύψουμε πίσω από μια φαινομενικά αθώα ενέργεια. Έτσι σκεφτήκαμε ένα σενάριο στο οποίο ο μαθητής θα έστελνε ένα email στον admin που θα του έλεγε να επισκεφθεί το dragonfly.puppies.chatzi.org/cv όπου είναι αναρτημένο το βιογραφικό του φοιτητή με σκοπό να τον βοηθήσει στο να βρει μια πρακτική πάνω στο web security. 

```
Με ενδιαφέρει πολύ το μάθημα της Προστασίας και Ασφάλειας και το
παρακολουθώ αυτήν την περίοδο οπότε επειδή είστε και ο καθηγητής του
μαθήματος θα ήθελα να σας ρωτήσω αν θα μπορούσατε να με βοηθήσετε να βρω
μια πρακτική πάνω σε αυτό το κομμάτι.

Σας παραθέτω και το βιογραφικό μου στο site μου:

http://dragonfly.puppies.chatzi.org/cv
```
Πατώντας στο link ο admin μεταφερόταν όντως στην σελίδα cv όπου και του άνοιγε το pdf με το πραγματικό βιογραφικό. Αυτό που δεν ήξερε ο admin ήταν ότι κατά τη διάρκεια της φόρτωσεις του pdf, ένα iframe τον οδήγησε στο URL του μαθήματος (προκειμένου να μπορέσουμε να εκτελέσουμε το injection του κώδικα javascript), έπειτα έστειλε ένα post request στο modules/phpbb/reply.php με πεδίο reply το ένα script που πρώτα του έπαιρνε το cookie (document.cookie) έπειτα το προσαρτούσε σε ένα get request προς το http://dragonfly.puppies.chatzi.org/cv/cookie.php?cookie και τέλος έστελνε το cookie στο cookie.php που στη συνέχεια το έγραφε σε ένα txt αρχείο. Ωστόσο εκείνος σε όλη τη διάρκεια αυτής της διαδικασία βρισκόταν στο pdf (window.top.location.href).

Αφού καταφέραμε να πάρουμε το cookie στη συνέχεια έπρεπε να βρούμε πως θα μπούμε το οποίο φαινόταν προφανές στην αρχή αλλά αποδείχτηκε επίσης λίγο χρονοβόρο. Τελικά καταφέραμε να συνδεθούμε μέσω των inspect tools στο λογιαρασμό του admin και αλλάζοντας τον κωδικό του σε "helloworld" είχαμε, πλέον, πρόσβαση στην ιστοσελίδα ως admin.

### Full Defacement

Δοκιμάστηκαν πολλές τεχνικές για την επίτευξη του full defacement της εφαρμογής (κοινώς αλλαγή του index.php page). Αρχικά δοκιμάσαμε κάποιες rfi τεχνικές στις σελίδες work.php και import.php. Συγκεκριμένα προσπαθήσαμε να τρέξουμε κάποιους κώδικες που θα παραποιούσαν την αρχική σελίδα αλλά δεν τα καταφέραμε. 

Τελικά το full defacement επήλθε μέσω xss στη σελίδα adminannouncements.php. Συγκεκριμένα παρατηρήθηκε πως το module αυτό δεν είχε προστατευθεί από xss επιθέσεις καθώς μπορούσε κανείς να τρέξει javascript κώδικα στον τίτλο του announcement. Έπειτα έγιναν σαφή τα χαρακτηριστικά του module αυτού: 

1) Announcemnts μπορεί να κάνει μόνο ο admin
2) Τα announcements εμφανίζονται στην αρχική σελίδα της εφαρμογής. 

Αν λοιπόν κάπως καταφέρναμε να φορτώσουμε κάποιο javascript κώδικα στο announcment θα μπορούσαμε να επηρεάσουμε τη σελίδα του openeclass για κάθε χρήστη και να πετύχουμε το full defacement. Μετά από αρκετές προσπάθειες με payloads καταφέραμε να αλλάζουμε την αρχική σελίδα σε μία εικόνα του macdemarco. 

### XSS επιθέσεις

Σημαντικο είναι να αναφερθεί πως το full defacement της εφαρμογής το πετύχαμε την τελευταία μέρα της προθεσμίας. Όλες τις προηγούμενες, λοιπόν, εκτελούσαμε άλλες επιθέσεις έχοντας πρόσβαση στη site σαν admin από πολύ νωρίς. Όπως αναφέρθηκε προηγουμένος η σελίδα reply.php ήταν ευάλωτη σε xss επιθέσεις. Μέσω αυτής της σελίδας καταφέραμε να βρούμε αποκτήσουμε το cookie. Πειραματιστήκαμε αρκετά με εκείνη τη σελίδα στην προσπάθεια μας, αρχικά, να εκτελέσουμε από εκεί το full defacement. Ωστόσο στην πορεία καταλάβαμε οτι δεν προχωρούσε αυτό και αρκεστήκαμε στο κλέψιμο του cookie (http://dragonfly.puppies.chatzi.org/cv) αλλά και σε ένα αντίστοιχο attack που είχε λίγο περισσότερη πλάκα, (http://dragonfly.puppies.chatzi.org/projects) όπου και μπαίνοντας γινόμαστε redirect στο reply.php και φορτώνουμε στο πεδίο της απάντησης το παιχνίδι με το δεινοσαυράκι που εμφανίζεται στο google.com όταν δεν έχεις πρόσβαση στο ίντερνετ. 

Άλλες xss επιθέσεις κάναμε στα:

http://ouzovissino.csec.chatzi.org/modules/work/work.php
http://ouzovissino.csec.chatzi.org/modules/dropbox/index.php
http://ouzovissino.csec.chatzi.org/modules/admin/adminannouncements.php
http://ouzovissino.csec.chatzi.org/modules/admin/listcours.php
http://ouzovissino.csec.chatzi.org/modules/phpbb/index.php
http://ouzovissino.csec.chatzi.org/modules/conference/conference.php
http://ouzovissino.csec.chatzi.org/modules/forum_admin/forum_admin.php
http://ouzovissino.csec.chatzi.org/modules/phpbb/viewforum.php 

Το site δεν ήταν εντελώς ασφαλησμένο για xss επιθέσεις καθώς πέραν του full defacement στις περισσότερες από τις παραπάνω σελίδες καταφέραμε να εκτελέσουμε είτε κάποιον javascript κώδικα είτε κάποιο περίεργο payload. Οι σημαντικότερες αλλαγές βρίσκονται στα dropbox/index.php και work/work/php.

### CSRF επιθέσεις

Στις CSRF επιθέσεις η ομάδα ouzovissino ήταν καλά προετοιμασμένη και έχοντας βάλει token στα περισσότερα POST και GET αιτήματα μας έκανε τη ζωή δύσκολη. Παρόλαυτά καταφέραμε να βρούμε 2 ευπάθειες:

http://ouzovissino.csec.chatzi.org/modules/forum_admin/forum_admin.php
http://ouzovissino.csec.chatzi.org/modules/work/work.php

Συγκεκριμένα αυτές οι 2 ενέργειες αναφερόντουσαν σε GET αιτήματα που δεν είχαν προστατευθεί με token στο URL. Καταφέραμε να προκαλέσουμε τη διαφραφή ενός assignment καθώς και τη διαγραφή μιας κατηγορίας ενός forum. Αυτό που επίσης προσπαθήμαε ήταν κάπως να κλέψουμε το token του χρήστη καθώς αντιληφθήκαμε οτι 
1) το token δεν άλλαζε παρά μόνο αν γινόταν εκ νέου login 
2) η μεταβλητή token είχε το προφανές όνομα **token**

Έτσι προσπαθήσαμε κάπως να κάνουμε τον χρήστη να κοινοποιήσει το token του έτσι ώστε να μπορέσουμε να το χρησιμοποιήσουμε και σε άλλα ατιήματα όμως δεν τα καταφέραμε. 

### SQLI επιθέσεις 

## Subverting application logic
Προσπαθήσαμε κάνοντας login στο αντίπαλο site με 

  • drunkadmin'—
  • drunkadmin'OR 1=1—
  • drunkadmin'UNION SELECT password, null FROM users WHERE username='drunkadmin'—
  • drunkadmin' AND (SELECT COUNT(*) FROM users)=1—
  • drunkadmin' AND SLEEP(10)—
  • drunkadmin'AND (SELECT 1/0 FROM users WHERE username='drunkadmin')—
  • drunkadmin' UNION SELECT password, null FROM users WHERE user_id='1'—
  • drunkadmin'AND (SELECT 1/0 FROM users WHERE user_id='1')—
  • drunkadmin'; DROP TABLE user—
  • drunkadmin' AND LENGTH(password)=0—
  • drunkadmin'/*' OR '1'='1'#
  • drunkadminUNION SELECT 1, 'admin', ''—
  • drunkadmin' AND (SELECT SUBSTR(password, 1, 1) FROM users WHERE username= drunkadmin')='b'—
  • drunkadmin' AND SUBSTRING(password, 1, 1)='a'—
  • drunkadmin''; INSERT INTO log_table (log_message) VALUES ('SQL injected'); --
  • drunkadmin OR 1=1
  • username : " or ""=" και password : " or ""="

## Retrieving hidden data
  • http://ouzovissino.csec.chatzi.org/courses/TMA100/logins?=user_id=1'UNION SELECT username, user_password FROM users--'
  • http://ouzovissino.csec.chatzi.org/courses/TMA100/users?=user_id=1'UNION SELECT username, user_password FROM users--'
  • http://ouzovissino.csec.chatzi.org/index.php/user?user_id=1';DROP TABLE user--
  • http://ouzovissino.csec.chatzi.org/modules/dropbox/index.php?upload=1'OR 1=1 AND (SELECT 1/0 FROM users)--'
  • http://ouzovissino.csec.chatzi.org/modules/dropbox/index.php?upload=1' AND SLEEP(5)--Εδώ ειδα οντως να περιμενει
  • https://ouzovissino.csec.chatzi.org/modules/dropbox/index.php?upload=1 ' OR (SELECT 1/0 FROM pg_statistic)--'
  • http://ouzovissino.csec.chatzi.org/index.php/user?user_id=1 ' AND SLEEP(5)



Σε πολλές από τις δοκιμές που αναφέραμε μας μετέφεραν σε αυτή εδώ τη σελίδα, https://ouzovissino.csec.chatzi.org/index.php/user?username=drunkadmin, που όμως δοκιμάζοντας ξανά Subverting application logic μας γυρνούσε την “original” σελίδα του eclass. Συμπερασματικά, ο,τι δοκιμές κάναμε με SQL Injection απέτυχαν!
