CREATE TABLE IF NOT EXISTS licenses(
 id BIGSERIAL PRIMARY KEY,license_key_hash CHAR(64) UNIQUE NOT NULL,status VARCHAR(20) NOT NULL DEFAULT 'active',plan VARCHAR(80) NOT NULL DEFAULT 'standard',domain VARCHAR(255),expires_at TIMESTAMPTZ,max_activations INTEGER NOT NULL DEFAULT 1,created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);
CREATE TABLE IF NOT EXISTS activations(
 id BIGSERIAL PRIMARY KEY,license_key_hash CHAR(64) NOT NULL,domain VARCHAR(255) NOT NULL,installation_id CHAR(64) NOT NULL,last_seen_at TIMESTAMPTZ NOT NULL,created_at TIMESTAMPTZ NOT NULL,UNIQUE(license_key_hash,installation_id)
);
CREATE INDEX IF NOT EXISTS activations_license_idx ON activations(license_key_hash);
