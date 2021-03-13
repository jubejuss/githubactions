# Github actions

See repo on tehtud moel, et oma arvutist pushitakse githubi ja sealt sobilikku serverisse.  
Kasutatud on Apleboy skripti.  
Töö peamiselt õppeeesmärgil.

Setupi täpsem juhis siin:  
[https://www.youtube.com/watch?v=gW1TDirJ5E4](https://www.youtube.com/watch?v=gW1TDirJ5E4)

Siinne juhis:

1. Vaja on leida Githubist Appleboy SSH Action – SSH Remote Commands [https://github.com/marketplace/actions/ssh-remote-commands](https://github.com/marketplace/actions/ssh-remote-commands)
2. Tee remote serveri ja githubi jaoks ssh key'd
3. Kopi private key, mine Githubi projekti setingutesse ja klikka Secret jaotusel ja "Add new secret"
4. Pane nimi ja jäta see meelde, et seda läheb hiljem vaja (siis tuled tagasi ja võtad selle siit), lisaks muidugi peisti key.
5. Seejärel kopi (enda arvutist) public key
6. Nüüd logime ssh abil remote serverisse ja tekitame sinna (kui veel pole) ja siis avame vim ~/.ssh/authorized_keys ja lisame enne kopitud public key.
7. Nüüd võime serverisse teha .github folderi. Mina tegin konkreetse projekti rootfolderisse. Sinna omakorda folder workflows (.gtihub/workflows) ja sinna touch .gtihub/workflows/first_workflow.yml  
   Seejärel ava see first_workflow.yml ja sinna sisse tulebki kirjutada see Appleboy skriptist tekitatud kood:

```yml
name: Deployment Workflow
on:
  push:
    branches: [main]

jobs:
  job_one:
    name: Deployment
    runs-on: ubuntu-latest
    steps:
      - name: testing tigu server ssh connection
        uses: appleboy/ssh-action@master
        with:
          host: 213.180.26.246
          username: juho.kalberg
          key: ${{ secrets.SSH_GIT_SECRET }}
          port: 22
          script: |
            cd public_html/githubactions
            git pull origin main
            git status
```

Tasub tähelepanu pöörata, et see host võib tavajuhul olla ka veebiaadress.
Siin näites toodud `key: ${{ secrets.SSH_GIT_SECRET }}`ongy see nimi, mille palusin ülal meelde jätta.

Kokkuvõttes panime me serveri ja Githubi SSH abil suhtlema ja lisasime serverisse vastava skripti.

Lisaks Bootstrapi install.
Starter: [https://github.com/twbs/bootstrap-npm-starter](https://github.com/twbs/bootstrap-npm-starter)

1. Kloonin repo siia root folderisse, mistap tekib bootsrap starter folder
2. Kustutan sealt .git'i rm -rf /bootstrap-npm-starter
3. Kopin bootstrapi failid ümber
