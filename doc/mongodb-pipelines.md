# MongoDB Aggregation Pipelines

## Col·lecció: `demo.logs`

Cada document té aquesta estructura:
```json
{
  "url":       "/logger.php",
  "method":    "GET",
  "usuari":    null,
  "timestamp": ISODate("2026-05-08T10:35:36Z"),
  "navegador": "Mozilla/5.0 ...",
  "ip":        "172.18.0.1"
}
```

---

## 1. Total d'accessos

```php
$total = $collection->countDocuments([]);
```

Equivalent en MongoDB Shell:
```js
db.logs.countDocuments({})
```

---

## 2. Pàgines més visitades

```php
$collection->aggregate([
    ['$group' => ['_id' => '$url', 'total' => ['$sum' => 1]]],
    ['$sort'  => ['total' => -1]],
    ['$limit' => 10],
]);
```

**Explicació del pipeline:**
| Estadi | Funció |
|--------|--------|
| `$group` | Agrupa tots els documents per `url` i suma 1 per cada un |
| `$sort`  | Ordena de més a menys visites |
| `$limit` | Retorna només els 10 primers |

---

## 3. Usuaris més actius

```php
$collection->aggregate([
    ['$match' => ['usuari' => ['$ne' => null]]],
    ['$group' => ['_id' => '$usuari', 'total' => ['$sum' => 1]]],
    ['$sort'  => ['total' => -1]],
    ['$limit' => 10],
]);
```

**Explicació del pipeline:**
| Estadi | Funció |
|--------|--------|
| `$match` | Exclou els documents sense usuari autenticat |
| `$group` | Agrupa per nom d'usuari i compta accessos |
| `$sort`  | Ordena de més a menys actius |
| `$limit` | Retorna els 10 primers |

---

## 4. Accessos agrupats per dia

```php
$collection->aggregate([
    ['$group' => [
        '_id'   => ['$dateToString' => ['format' => '%Y-%m-%d', 'date' => '$timestamp']],
        'total' => ['$sum' => 1],
    ]],
    ['$sort' => ['_id' => 1]],
]);
```

**Explicació del pipeline:**
| Estadi | Funció |
|--------|--------|
| `$group` | Converteix el timestamp a string `YYYY-MM-DD` i agrupa per dia |
| `$sort`  | Ordena cronològicament |

---

## 5. Filtres combinats (amb `$match` dinàmic)

Quan l'usuari aplica filtres al panell, s'afegeix un `$match` al principi:

```php
// Filtre per data
$match['timestamp'] = [
    '$gte' => new UTCDateTime(strtotime('2026-05-08') * 1000),
    '$lt'  => new UTCDateTime(strtotime('2026-05-09') * 1000),
];

// Filtre per usuari
$match['usuari'] = 'hugo';

// Filtre per pàgina (cerca parcial)
$match['url'] = ['$regex' => 'admin', '$options' => 'i'];
```

El `$match` sempre va **primer** al pipeline per reduir el nombre de documents abans d'agregar.