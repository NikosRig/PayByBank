set -e

mongosh <<EOF
use $DB

db.createUser({
  user: "$DB_USER",
  pwd: "$DB_USER_PASSWORD",
  roles: [{
    role: 'readWrite',
    db: "$DB"
  }]
})
EOF