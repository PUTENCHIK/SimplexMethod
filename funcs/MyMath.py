from exceptions.NullArguments import NullArguments
from exceptions.NegativeArguments import NegativeArguments


class MyMath:

    @staticmethod
    def gcd(a: int, b: int):
        if a < 0 or b < 0:
            raise NullArguments()
        elif a == 0 or b == 0:
            raise NegativeArguments()
        while a != b:
            if a > b:
                a -= b
            else:
                b -= a
        return a

    @staticmethod
    def lcm(a: int, b: int):
        return a * b // MyMath.gcd(a, b)
