# Inscription 
- scenario par défaut:
    - mail
    - mdp 
    - btn envoyer email => url miantso fonction insert 

    # traitement 
    - hacher le mdp
    - générer un jeton 
    - construire le lien 
    - insertion mdp haché, mail, jeton et date d'expiration (parametrable) dans la table : jeton_inscription
    - fonction envoie email lien 
    - fonction validation inscription : ao anaty table ve lay token dia mbola tsy expiré ve, si oui insertion dans la table user()

# Authentification multifacteur avec confirmation PIN sur email 
- scenario par défaut:
    - mail
    - mdp 
    - btn envoyer PIN  => url miantso fonction  

    # traitement
    - fonction checkLogin(mail,mdp)
    - générer code PIN (et date d'expiration)
    - insérer code PIN dans table pin_authentification
    - fonction envoie code PIN any am mail
    - (user entre code PIN)
    - fonction estValidePIN => mitovy amlay généré ve sady mbola tsy expiré, si oui, login 
    - login => 
        - générer jeton 
        - insertion dans jeton_authentification 
    


