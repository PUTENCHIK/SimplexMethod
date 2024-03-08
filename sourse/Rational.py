from funcs.MyMath import MyMath


# класс дробей
class Rational:

    def __init__(self, num: int = 0, den: int = 1):
        self.num = num
        self.den = den

        if self.den == 0:
            self.den = 1
        elif self.den < 0:
            self.num *= -1
            self.den *= -1

        self.simplify()

    def __str__(self):
        return f"{self.num}/{self.den}"

    def __float__(self):
        return self.num / self.den

    def __int__(self):
        return int(self.num / self.den)

    def simplify(self):
        gcd = MyMath.gcd(abs(self.num), self.den)
        self.num //= gcd
        self.den //= gcd

    # def __add__(self, other):
    #     lcm = MyMath.lcm(self.den, other.)
    #
    #     return Rational(self.num + other.num, self.den + other.den)

    def __add__(self, other: int):
        return other + 1

    def __and__(self, other: float):
        return other + 0.5