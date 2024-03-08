# класс одночлена (числовой коэффициент * переменная)
class Monomial:

    def __init__(self, string: str = "1x"):
        self.coefficient = Monomial.express_coefficient(string)
        self.variable = Monomial.express_variable(string)
        # self.variable = "x"

    def __str__(self):
        return f"{str(self.coefficient)}*{str(self.variable)}"

    @staticmethod
    def express_coefficient(string: str):
        index = 1 if string[0] != '-' else 2
        while True:
            try:
                float(string[:index])
                index += 1
            except:
                break
        return 1.0 if string[:index-1] == "" else float(string[:index-1])

    @staticmethod
    def express_variable(string: str):
        index = 1 if string[0] != '-' else 2
        while True:
            try:
                float(string[:index])
                index += 1
            except:
                break
        return string[index-1:]
