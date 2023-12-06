import java.math.BigDecimal;
import java.time.LocalDate;
import java.time.format.DateTimeFormatter;
import java.util.*;
import java.util.stream.Collectors;

class Pessoa {
    String nome;
    LocalDate dataNascimento;

    public Pessoa(String nome, LocalDate dataNascimento) {
        this.nome = nome;
        this.dataNascimento = dataNascimento;
    }
}

class Funcionario extends Pessoa {
    BigDecimal salario;
    String funcao;

    public Funcionario(String nome, LocalDate dataNascimento, BigDecimal salario, String funcao) {
        super(nome, dataNascimento);
        this.salario = salario;
        this.funcao = funcao;
    }
}

public class Principal {
    public static void main(String[] args) {
        List<Funcionario> funcionarios = new ArrayList<>();
       	
        funcionarios.add(new Funcionario("Maria", LocalDate.of(2000, 10, 18), new BigDecimal("2009.44"), "Operador"));
        funcionarios.add(new Funcionario("Joao", LocalDate.of(1990, 5, 12), new BigDecimal("2284.38"), "Operador"));
        funcionarios.add(new Funcionario("Caio", LocalDate.of(1961, 5, 2), new BigDecimal("9836.14"), "Coordenador"));
		funcionarios.add(new Funcionario("Miguel", LocalDate.of(1988, 10, 14), new BigDecimal("19119.88"), "Diretor"));
        funcionarios.add(new Funcionario("Alice", LocalDate.of(1995, 1, 5), new BigDecimal("2234.68"), "Recepcionista"));
        funcionarios.add(new Funcionario("Heitor", LocalDate.of(1999, 11, 19), new BigDecimal("1582.72"), "Operador"));
		funcionarios.add(new Funcionario("Arthur", LocalDate.of(1993, 3, 31), new BigDecimal("4071.84"), "Contador"));
        funcionarios.add(new Funcionario("Laura", LocalDate.of(1994, 7, 8), new BigDecimal("3017.45"), "Gerente"));
        funcionarios.add(new Funcionario("Heloisa", LocalDate.of(2003, 5, 24), new BigDecimal("1606.85"), "Eletricista"));
		funcionarios.add(new Funcionario("Helena", LocalDate.of(1996, 9, 2), new BigDecimal("2799.93"), "Gerente"));
       
        funcionarios.removeIf(funcionario -> funcionario.nome.equals("Joao"));

        DateTimeFormatter formatter = DateTimeFormatter.ofPattern("dd/MM/yyyy");
        for (Funcionario funcionario : funcionarios) {
            System.out.printf("Nome: %s, Data de Nascimento: %s, Salário: %,.2f, Função: %s%n",
                    funcionario.nome, funcionario.dataNascimento.format(formatter),
                    funcionario.salario, funcionario.funcao);
        }

        funcionarios.forEach(funcionario -> funcionario.salario = funcionario.salario.multiply(new BigDecimal("1.1")));

        Map<String, List<Funcionario>> funcionariosPorFuncao = funcionarios.stream()
                .collect(Collectors.groupingBy(Funcionario::getFuncao));

        funcionariosPorFuncao.forEach((funcao, lista) -> {
            System.out.println("Função: " + funcao);
            lista.forEach(f -> System.out.println("  " + f.nome));
        });

        int[] mesesAniversario = {10, 12};
        LocalDate hoje = LocalDate.now();
        funcionarios.stream()
                .filter(funcionario -> Arrays.stream(mesesAniversario).anyMatch(mes -> funcionario.dataNascimento.getMonthValue() == mes))
                .forEach(funcionario -> System.out.println("Aniversariante: " + funcionario.nome));

        LocalDate dataAtual = LocalDate.now();
        Optional<Funcionario> funcionarioMaisVelho = funcionarios.stream()
                .min(Comparator.comparingInt(f -> (int) f.dataNascimento.until(dataAtual).toTotalDays()));
        funcionarioMaisVelho.ifPresent(funcionario -> {
            int idade = (int) funcionario.dataNascimento.until(dataAtual).toTotalDays() / 365;
            System.out.printf("Funcionário mais velho: %s, Idade: %d anos%n", funcionario.nome, idade);
        });

        funcionarios.sort(Comparator.comparing(funcionario -> funcionario.nome));
        System.out.println("Lista de funcionários por ordem alfabética:");
        funcionarios.forEach(funcionario -> System.out.println("  " + funcionario.nome));

        BigDecimal totalSalarios = funcionarios.stream().map(Funcionario::getSalario).reduce(BigDecimal.ZERO, BigDecimal::add);
        System.out.printf("Total dos salários: %,.2f%n", totalSalarios);

        BigDecimal salarioMinimo = new BigDecimal("1212.00");
        funcionarios.forEach(funcionario -> {
            BigDecimal salariosMinimos = funcionario.salario.divide(salarioMinimo, 2, BigDecimal.ROUND_DOWN);
            System.out.printf("%s ganha %.2f salários mínimos%n", funcionario.nome, salariosMinimos.doubleValue());
        });
    }
}
