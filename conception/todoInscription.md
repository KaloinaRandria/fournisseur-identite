# CLASSES : 
## JetonInscription
### attributs (+ getters & setters & constructeurs )
- mail
- mdp (hashé)
- nom
- date_naissance
- jeton 
- date_insertion
- date_expiration
- duree_jeton : default(5 minutes)

### setter perso
- setDateExpirationPerso()=> date_insertion + duree_jeton

### constructeur
- miantso setDateExpirationPerso


### méthodes
#### Fonctionnalité: INSCRIPTION
- non static insert(connection): miantso getDateExpiration atao anaty table jeton_inscription
- genererJeton()
- buildUrlInscription(jeton)
- isExpiredJeton(dateheure click)=> dateheure click > getDateExpiration()
- validerInscription()=> miantso isExpiredJeton, manamboatra objet utilisateur, dia manao insert utilisateur 


## Utilisateur 
### attributs
- id
- mail
- mdp
- nom
- date_naissance

### méthodes
- non static insert(connection)
- getById(connection,id)



# SERVICES : 
## HasherService
### méthodes:
- hashMdp(mdp)
- unhashMdp(mdp_hashed)

## MailService (mampiasa classe PHPMailer)
### méthodes:
- sendEmail(PHPMailer)