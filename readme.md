# Simulador de métodos da camada de enlace

### Algoritmos de detecção e correção de erros na camada de enlace

Projeto desenvolvido na disciplina de rede de computadores para simulação de alguns métodos utilizados na a detecção e correção de erros na camada de enlace da arquitetura TCP/IP. O simulador busca mostrar como os algoritmos tratam os dados (frames, quadros) durante o processo de comunicação entre um emissor e um receptor, na tentativa de encontrar erros e se possível corrigi-los.

### Os métodos disponíveis são :

- Paridade Simples (par)
- Paridade Dupla (par)
- Cyclic Redundancy Check - CRC
- Checksum
- Hamming (realiza correção)

### Pré-requisito

- php >= 5.4 instalado

### Executando

- Baixe o projeto e dentro da pasta do projeto execute

        php -S localhost:8000

- Agora bastar acessar **localhost:8000** para utilizar o simulador