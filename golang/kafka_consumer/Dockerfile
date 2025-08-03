FROM golang:1.22 AS builder
WORKDIR /app

COPY go.mod .
COPY go.sum .
RUN go mod download

COPY . .
RUN go build -o app . && ls -lh app

FROM debian:bookworm-slim
WORKDIR /app
COPY --from=builder /app/app .
RUN ls -lh /app/