# Coding challenge — Laravel + Angular Senior

## Scenario

Stai completando una piattaforma che riceve richieste di elaborazione (`processing requests`) via API.
Ogni richiesta appartiene a un progetto, viene creata in stato `pending`, poi deve essere processata in background tramite **queue**.

Il frontend Angular deve permettere di:
- autenticarsi
- consultare la lista delle richieste
- creare una nuova richiesta
- vedere il dettaglio
- ritentare una richiesta fallita
- visualizzare statistiche sintetiche

## Cosa è già presente nel test

### Backend
- modelli principali
- migration base
- seeder base
- endpoint `POST /api/login`
- endpoint `GET /api/me`
- route API principali già dichiarate
- job e servizi come scheletro incompleto

### Frontend
- routing base
- login minimale
- layout minimale
- servizi API iniziali
- schermate base da completare

## Cosa devi implementare

### Backend
Implementa questi endpoint autenticati:

- `GET /api/projects`
- `GET /api/processing-requests`
- `POST /api/processing-requests`
- `GET /api/processing-requests/{id}`
- `POST /api/processing-requests/{id}/retry`
- `GET /api/dashboard/stats`

### Requisiti backend

#### Projects
`GET /api/projects`
- restituire solo progetti attivi
- usare cache applicativa con TTL 5 minuti
- invalidare la cache quando necessario

#### Create processing request
`POST /api/processing-requests`
- validare input
- `project_id` deve esistere e riferirsi a un progetto attivo
- `reference` univoco all'interno del progetto
- `payload_json` deve contenere:
  - `customer`
  - `items` array non vuoto
- creare la richiesta con stato `pending`
- dispatchare il job di elaborazione

Payload esempio:
```json
{
  "project_id": 1,
  "reference": "ACME-001",
  "payload_json": {
    "customer": "ACME",
    "items": [
      { "sku": "A1", "qty": 2, "price": 10 },
      { "sku": "B2", "qty": 1, "price": 30 }
    ]
  }
}
```

#### List processing requests
`GET /api/processing-requests`
- paginazione
- filtri: `status`, `project_id`, `reference`, `date_from`, `date_to`
- ordinamento almeno per `created_at`
- includere progetto e creatore

#### Detail
`GET /api/processing-requests/{id}`
- includere dati completi di richiesta, progetto, creatore
- usare cache breve solo per richieste `completed`

#### Retry
`POST /api/processing-requests/{id}/retry`
- consentito solo per richieste `failed`
- riportare la richiesta a `pending`
- rimettere il job in coda

#### Dashboard
`GET /api/dashboard/stats`
Restituire almeno:
- totale richieste
- conteggio per stato
- richieste create oggi
- tempo medio di elaborazione delle completed

Usare cache applicativa con TTL 2 minuti.

### Queue
Il job `ProcessProcessingRequestJob` deve:
- impostare stato `processing`
- elaborare il payload
- produrre un `result_json`
- impostare `processed_at`
- in caso di errore impostare `failed` e valorizzare `error_message`

Risultato atteso per l'esempio sopra:
```json
{
  "customer": "ACME",
  "items_count": 2,
  "total_amount": 50,
  "vat": 11,
  "grand_total": 61
}
```

Regole minime:
- `items` non vuoto
- `qty > 0`
- `price >= 0`

### Frontend Angular
Realizza almeno:

#### Login
- usa `POST /api/login`
- salva il token bearer
- usa `GET /api/me` per recuperare l'utente autenticato

#### Lista richieste
- tabella con `id`, `reference`, `project`, `status`, `created_at`, `processed_at`
- filtro per stato e progetto
- refresh manuale

#### Crea richiesta
- form con progetto, reference, payload JSON
- submit verso API
- gestione errori

#### Dettaglio
- mostra payload originale
- mostra risultato
- pulsante retry se `failed`

#### Dashboard
- mostra statistiche sintetiche

## Vincoli
- non serve una UI raffinata
- cura struttura del codice e gestione errori
- puoi aggiungere classi, servizi, test, DTO, action, policy, resource, interceptor

## Consegna
Fornisci:
- repository o zip finale
- istruzioni di avvio
- breve nota architetturale
- eventuali tradeoff e miglioramenti futuri
